-- ═══════════════════════════════════════════════════════════
--  FastPlay — Base de datos completa
-- ═══════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS fastplay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fastplay;

-- ── Usuarios ──────────────────────────────────────────────
CREATE TABLE users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(180) NOT NULL UNIQUE,
    phone      VARCHAR(20)  DEFAULT NULL,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('player','captain','admin') NOT NULL DEFAULT 'player',
    position   ENUM('portero','defensa','centrocampista','delantero','') DEFAULT '',
    city       VARCHAR(100) DEFAULT '',
    age        TINYINT UNSIGNED DEFAULT NULL,
    height     SMALLINT UNSIGNED DEFAULT NULL,
    weight     TINYINT UNSIGNED DEFAULT NULL,
    photo      VARCHAR(255) DEFAULT 'default.png',
    is_banned  TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB;

-- ── Equipos ────────────────────────────────────────────────
CREATE TABLE teams (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120) NOT NULL,
    city        VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    captain_id  INT UNSIGNED NOT NULL,
    shield      VARCHAR(255) DEFAULT NULL,
    reputation  SMALLINT UNSIGNED NOT NULL DEFAULT 100,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (captain_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_city (city)
) ENGINE=InnoDB;

-- ── Jugadores de equipo ────────────────────────────────────
CREATE TABLE team_players (
    team_id   INT UNSIGNED NOT NULL,
    user_id   INT UNSIGNED NOT NULL,
    role      ENUM('player','cocaptain','captain') NOT NULL DEFAULT 'player',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (team_id, user_id),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Campos ────────────────────────────────────────────────
CREATE TABLE fields (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    address      VARCHAR(255) NOT NULL,
    city         VARCHAR(100) NOT NULL,
    lat          DECIMAL(10,7) DEFAULT NULL,
    lng          DECIMAL(10,7) DEFAULT NULL,
    surface      ENUM('grass','artificial','futsal') DEFAULT 'artificial',
    is_certified TINYINT(1) NOT NULL DEFAULT 0,
    status       ENUM('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB;

-- ── Temporadas ────────────────────────────────────────────
CREATE TABLE seasons (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(80)  NOT NULL,
    start_date DATE NOT NULL,
    end_date   DATE NOT NULL,
    status     ENUM('upcoming','active','finished') NOT NULL DEFAULT 'upcoming'
) ENGINE=InnoDB;

-- ── Ligas ─────────────────────────────────────────────────
CREATE TABLE leagues (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120) NOT NULL,
    type       ENUM('friendly','pro') NOT NULL DEFAULT 'friendly',
    city       VARCHAR(100) NOT NULL,
    season_id  INT UNSIGNED DEFAULT NULL,
    start_date DATE NOT NULL,
    end_date   DATE NOT NULL,
    status     ENUM('upcoming','active','finished') NOT NULL DEFAULT 'upcoming',
    prize_pool DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE SET NULL,
    INDEX idx_type   (type),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ── Clasificación ─────────────────────────────────────────
CREATE TABLE league_standings (
    league_id      INT UNSIGNED NOT NULL,
    team_id        INT UNSIGNED NOT NULL,
    played         TINYINT UNSIGNED NOT NULL DEFAULT 0,
    won            TINYINT UNSIGNED NOT NULL DEFAULT 0,
    drawn          TINYINT UNSIGNED NOT NULL DEFAULT 0,
    lost           TINYINT UNSIGNED NOT NULL DEFAULT 0,
    goals_for      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    goals_against  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    points         TINYINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (league_id, team_id),
    FOREIGN KEY (league_id) REFERENCES leagues(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id)   REFERENCES teams(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Partidos ──────────────────────────────────────────────
CREATE TABLE matches (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    home_team_id INT UNSIGNED NOT NULL,
    away_team_id INT UNSIGNED NOT NULL,
    field_id     INT UNSIGNED DEFAULT NULL,
    league_id    INT UNSIGNED DEFAULT NULL,
    match_date   DATETIME NOT NULL,
    home_score   TINYINT UNSIGNED DEFAULT NULL,
    away_score   TINYINT UNSIGNED DEFAULT NULL,
    status       ENUM('pending','confirmed','finished','cancelled') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (home_team_id) REFERENCES teams(id),
    FOREIGN KEY (away_team_id) REFERENCES teams(id),
    FOREIGN KEY (field_id)     REFERENCES fields(id)  ON DELETE SET NULL,
    FOREIGN KEY (league_id)    REFERENCES leagues(id) ON DELETE SET NULL,
    INDEX idx_date   (match_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ── Jugadores del partido ──────────────────────────────────
CREATE TABLE match_players (
    match_id INT UNSIGNED NOT NULL,
    user_id  INT UNSIGNED NOT NULL,
    team_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (match_id, user_id),
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (team_id)  REFERENCES teams(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Alineaciones ──────────────────────────────────────────
CREATE TABLE match_lineups (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    match_id       INT UNSIGNED NOT NULL,
    team_id        INT UNSIGNED NOT NULL,
    user_id        INT UNSIGNED NOT NULL,
    is_starter     TINYINT(1) NOT NULL DEFAULT 1,
    jersey_number  TINYINT UNSIGNED DEFAULT NULL,
    UNIQUE KEY uq_match_player (match_id, user_id),
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id)  REFERENCES teams(id)   ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Estadísticas ──────────────────────────────────────────
CREATE TABLE stats (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    match_id     INT UNSIGNED NOT NULL,
    user_id      INT UNSIGNED NOT NULL,
    goals        TINYINT UNSIGNED NOT NULL DEFAULT 0,
    assists      TINYINT UNSIGNED NOT NULL DEFAULT 0,
    yellow_cards TINYINT UNSIGNED NOT NULL DEFAULT 0,
    red_cards    TINYINT UNSIGNED NOT NULL DEFAULT 0,
    minutes      SMALLINT UNSIGNED DEFAULT NULL,
    UNIQUE KEY uq_match_user (match_id, user_id),
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Sanciones ─────────────────────────────────────────────
CREATE TABLE sanctions (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    team_id   INT UNSIGNED NOT NULL,
    type      ENUM('warning','ban') NOT NULL DEFAULT 'warning',
    reason    TEXT DEFAULT NULL,
    issued_by INT UNSIGNED DEFAULT NULL,
    expires_at DATETIME DEFAULT NULL,
    season_id INT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id)   REFERENCES teams(id)   ON DELETE CASCADE,
    FOREIGN KEY (issued_by) REFERENCES users(id)   ON DELETE SET NULL,
    FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ── Logros ────────────────────────────────────────────────
CREATE TABLE achievements (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    icon        VARCHAR(10)  DEFAULT '🏅'
) ENGINE=InnoDB;

CREATE TABLE user_achievements (
    user_id        INT UNSIGNED NOT NULL,
    achievement_id INT UNSIGNED NOT NULL,
    earned_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, achievement_id),
    FOREIGN KEY (user_id)        REFERENCES users(id)        ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Chat ──────────────────────────────────────────────────
CREATE TABLE chat_rooms (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120) NOT NULL,
    type       ENUM('team','match_negotiation','direct') NOT NULL DEFAULT 'team',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE chat_room_members (
    room_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (room_id, user_id),
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)      ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE chat_messages (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id    INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    content    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)      ON DELETE CASCADE,
    INDEX idx_room (room_id)
) ENGINE=InnoDB;

-- ════════════════════════════════════════
--  Datos de ejemplo
-- ════════════════════════════════════════

INSERT INTO users (name, email, password, role, position, city) VALUES
('Admin FastPlay', 'admin@fastplay.com', '$2y$12$placeholder_change_me', 'admin', '', 'Madrid'),
('Carlos Gómez',   'carlos@demo.com',    '$2y$12$placeholder_change_me', 'captain', 'delantero', 'Madrid'),
('Luis Martínez',  'luis@demo.com',      '$2y$12$placeholder_change_me', 'player',  'defensa',   'Madrid'),
('Pablo Sánchez',  'pablo@demo.com',     '$2y$12$placeholder_change_me', 'captain', 'centrocampista', 'Barcelona');

INSERT INTO fields (name, address, city, surface, is_certified, status) VALUES
('Campo Municipal Norte', 'Av. del Norte 45', 'Madrid',    'artificial', 1, 'active'),
('Polideportivo Sur',     'C/ Sur 12',        'Madrid',    'grass',      1, 'active'),
('Pabellón Olímpico',     'Rambla 77',        'Barcelona', 'futsal',     1, 'active');

INSERT INTO seasons (name, start_date, end_date, status) VALUES
('Temporada 2026', '2026-01-15', '2026-06-30', 'active');

INSERT INTO leagues (name, type, city, season_id, start_date, end_date, status, prize_pool) VALUES
('Liga Pro Madrid 2026',     'pro',      'Madrid',    1, '2026-02-01', '2026-05-31', 'active', 400.00),
('Amistosa Primavera Madrid','friendly', 'Madrid',    1, '2026-03-01', '2026-04-30', 'active', 0),
('Liga Pro Barcelona 2026',  'pro',      'Barcelona', 1, '2026-02-15', '2026-06-15', 'active', 240.00);

INSERT INTO teams (name, city, captain_id, description, reputation) VALUES
('Los Galácticos FC', 'Madrid',    2, 'El mejor equipo del barrio', 120),
('FC Barcelona Night', 'Barcelona', 4, 'Fútbol nocturno en Bcn',   95);

INSERT INTO team_players (team_id, user_id, role) VALUES
(1, 2, 'captain'), (1, 3, 'player'), (2, 4, 'captain');

INSERT INTO league_standings (league_id, team_id, played, won, drawn, lost, goals_for, goals_against, points) VALUES
(1, 1, 3, 2, 1, 0, 7, 3, 7),
(1, 2, 3, 1, 1, 1, 4, 4, 4);

INSERT INTO matches (home_team_id, away_team_id, field_id, league_id, match_date, status) VALUES
(1, 2, 1, 1, DATE_ADD(NOW(), INTERVAL 3 DAY),  'confirmed'),
(2, 1, 3, 3, DATE_ADD(NOW(), INTERVAL 7 DAY),  'confirmed'),
(1, 2, 2, 1, DATE_SUB(NOW(), INTERVAL 7 DAY), 'finished');

UPDATE matches SET home_score = 2, away_score = 1 WHERE status = 'finished';

INSERT INTO achievements (name, description, icon) VALUES
('Primer partido', 'Jugaste tu primer partido en FastPlay', '⚽'),
('Goleador', 'Marcaste 5 goles en una temporada',           '🎯'),
('Fair Play',  'Cero tarjetas en 10 partidos',              '🤝'),
('Veterano',   '50 partidos jugados',                       '🏆');
