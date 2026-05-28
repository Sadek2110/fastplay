<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=device-width, initial-scale=1.0">
    <title>¡Bienvenido a FastPlay! Verifica tu correo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0c1510; font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #f8fafc; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0c1510; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #132219; border: 1px solid #223c2c; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #162c20, #0c1510); padding: 40px 20px; border-bottom: 1px solid #223c2c;">
                            <h1 style="margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -0.5px; color: #4ade80; text-shadow: 0 2px 10px rgba(74, 222, 128, 0.2);">
                                FastPlay
                            </h1>
                            <p style="margin: 5px 0 0 0; font-size: 14px; color: #a7f3d0; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                                Conecta · Compite · Gana
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: -0.5px;">
                                ¡Hola, <span style="color: #4ade80; text-shadow: 0 0 10px rgba(74, 222, 128, 0.1);"><?= e($name) ?></span>!
                            </h2>
                            <p style="margin: 0 0 20px 0; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
                                Te damos la más cálida bienvenida a **FastPlay**, la plataforma definitiva para organizar, buscar y competir en partidos de fútbol en Ceuta. 
                            </p>
                            <p style="margin: 0 0 30px 0; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
                                Para poder activar todas las funciones de tu cuenta (unirte a equipos, chatear con compañeros, solicitar partidos y conseguir logros), necesitamos confirmar tu dirección de correo electrónico.
                            </p>
                            
                            <!-- Call to Action -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 35px;">
                                <tr>
                                    <td align="center">
                                        <a href="<?= e($url) ?>" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; font-size: 16px; font-weight: 700; text-decoration: none; padding: 16px 40px; border-radius: 12px; border: 1px solid #10b981; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;">
                                            Verificar mi cuenta
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Text Link Alternative -->
                            <p style="margin: 0 0 10px 0; font-size: 13px; color: #94a3b8; line-height: 1.5;">
                                Si tienes problemas con el botón, copia y pega el siguiente enlace en tu navegador web:
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; word-break: break-all; line-height: 1.5;">
                                <a href="<?= e($url) ?>" target="_blank" style="color: #34d399; text-decoration: none; font-family: monospace;">
                                    <?= e($url) ?>
                                </a>
                            </p>
                            
                            <hr style="border: 0; border-top: 1px solid #223c2c; margin: 30px 0;">
                            
                            <p style="margin: 0; font-size: 14px; color: #94a3b8; line-height: 1.5;">
                                ¡Nos vemos en el terreno de juego!
                                <br>
                                <strong style="color: #ffffff;">El equipo de FastPlay</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #080f0b; padding: 30px 20px; border-top: 1px solid #223c2c;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #64748b; line-height: 1.5;">
                                Has recibido este correo porque te has registrado en FastPlay.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.5;">
                                © 2026 FastPlay Ceuta. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
