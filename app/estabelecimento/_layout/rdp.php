<div class="footer" style="background-color: var(--main-color, #f8f9fa); padding: 15px 0; position: relative; width: 100%; box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);">
    <div class="footer-info">
        <div class="container">
            <!-- Endereço Completo -->
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-12" style="text-align: center;">
                    <span style="font-size: 14px; color: var(--text-color, #333);"><?php echo $app['endereco_completo']; ?></span>
                </div>
            </div>

            <!-- Redes Sociais - Organizados Horizontalmente -->
            <div class="row">
                <div class="col-md-12" style="text-align: center;">
                    <div class="social" style="display: flex; justify-content: center; gap: 15px; align-items: center;">
                        <?php if( $app['contato_whatsapp'] ) { ?>
                            <a href="https://wa.me/55<?php echo $app['contato_whatsapp']; ?>" target="_blank" style="color: var(--whatsapp-color, #25D366);">
                                <i class="lni lni-whatsapp" style="font-size: 24px;"></i>
                            </a>
                        <?php } ?>
                        <?php if( $app['contato_facebook'] ) { ?>
                            <a href="<?php echo linker( $app['contato_facebook'] ); ?>" target="_blank" style="color: var(--facebook-color, #4267B2);">
                                <i class="lni lni-facebook-filled" style="font-size: 24px;"></i>
                            </a>
                        <?php } ?>
                        <?php if( $app['contato_instagram'] ) { ?>
                            <a href="<?php echo linker( $app['contato_instagram'] ); ?>" target="_blank" style="color: var(--instagram-color, #E4405F);">
                                <i class="lni lni-instagram-original" style="font-size: 24px;"></i>
                            </a>
                        <?php } ?>
                        <?php if( $app['contato_youtube'] ) { ?>
                            <a href="<?php echo linker( $app['contato_youtube'] ); ?>" target="_blank" style="color: var(--youtube-color, #FF0000);">
                                <i class="lni lni-youtube" style="font-size: 24px;"></i>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Direitos Autorais -->
   <!-- <div class="copyright" style="background-color: var(--secondary-color, #000000); padding: 10px 0; text-align: center; font-size: 12px; color: var(--text-secondary-color, #666);">
        <div class="container">
            <span>&copy; <?php echo date('Y'); ?> Todos os direitos reservados.</span>
        </div>
    </div>
</div>-->

<!-- Espaço menor para evitar interferência com o botão fixo -->
<div style="height: 15px;"></div>

<?php
// Função para calcular a luminosidade da cor
function get_text_color_based_on_background($hexColor) {
    $hex = str_replace('#', '', $hexColor);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $luminosity = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

    // Retorna branco (#FFFFFF) para cores escuras e preto (#000000) para cores claras
    return $luminosity > 0.5 ? '#000000' : '#FFFFFF';
}

// Configuração dinâmica das cores
$backgroundColor = $app['cor_predominante'];
$textColor = get_text_color_based_on_background($backgroundColor);
$textSecondaryColor = get_text_color_based_on_background($app['cor_secundaria']);

echo "<style>
    :root {
        --main-color: {$backgroundColor};
        --text-color: {$textColor};
        --secondary-color: {$app['cor_secundaria']};
        --text-secondary-color: {$textSecondaryColor};
        --whatsapp-color: #25D366;
        --facebook-color: #4267B2;
        --instagram-color: #E4405F;
        --youtube-color: #FF0000;
    }
</style>";
?>
