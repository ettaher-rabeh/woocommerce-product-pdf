<?php

// Ajouter bouton sous "Ajouter au panier"
add_action('woocommerce_after_add_to_cart_button', 'add_download_pdf_button');
function add_download_pdf_button() {
    global $product;
    echo '<a href="' . esc_url(add_query_arg(array(
        'download_product_pdf' => $product->get_id()
    ))) . '" class="button" style="margin-top:10px;background:#d32f2f;color:#fff;border-radius: 30px">📄 Télécharger la fiche produit</a>';
}

// Générer le PDF
add_action('init', 'generate_product_pdf');
function generate_product_pdf() {
    if (isset($_GET['download_product_pdf'])) {
        $product_id = intval($_GET['download_product_pdf']);
        $product = wc_get_product($product_id);

        if (!$product) return;

        // Infos produit
        $title      = $product->get_name();
        $sku        = $product->get_sku() ? $product->get_sku() : 'N/A';
        $desc       = $product->get_description();
        $image_id   = $product->get_image_id();
        $image_url  = wp_get_attachment_url($image_id);
        $categories = wc_get_product_category_list($product_id);
        $attributes = $product->get_attributes();

        // Logo du site
        $logo_url = wp_get_attachment_url(get_theme_mod('custom_logo'));

        // Pied de page
        $footer = "<p style='text-align:center;margin-top:30px;'>Adresse : Av Habib Bourguiba - Nabeul - 8000, Tunis<br>Tél : +216 53 370 270</p>";

        // Construire la liste des spécifications (attributs)
        $specs = "";
        if (!empty($attributes)) {
            $specs .= "<table width='100%' border='1' cellspacing='0' cellpadding='5' style='border-collapse:collapse;'>";
            $specs .= "<tr style='background:#f2f2f2;'>
                        <th style='text-align:left;'>Attribut</th>
                        <th style='text-align:left;'>Valeur</th>
                       </tr>";
            foreach ($attributes as $attr) {
                $specs .= "<tr>
                            <td>{$attr->get_name()}</td>
                            <td>" . implode(', ', $attr->get_options()) . "</td>
                           </tr>";
            }
            $specs .= "</table>";
        } else {
            $specs .= "<p>Aucune spécification disponible.</p>";
        }

        // HTML du PDF
        $html = "
        <div style='font-family:Arial, sans-serif;'>
            <div style='text-align:center;'>
                <img src='{$logo_url}' height='80'><br><br>
            </div>

            <table width='100%' cellspacing='0' cellpadding='10' border='0'>
                <tr>
                    <!-- Image du produit -->
                    <td width='40%' style='text-align:center; vertical-align:top;'>
                        <img src='{$image_url}' style='max-width:100%;height:auto;'>
                    </td>

                    <!-- Infos produit -->
                    <td width='60%' style='vertical-align:top;'>
                        <h2 style='color:#333;margin-top:0;'>{$title}</h2>
                        <p><strong>Référence (SKU) :</strong> {$sku}</p>
                        <p><strong>Catégorie(s) :</strong> {$categories}</p>
                    </td>
                </tr>
            </table>

            <h3>Description :</h3>
            <p>{$desc}</p>

            <h3>Spécifications :</h3>
            {$specs}

            {$footer}
        </div>";

        // Charger Dompdf
        require_once WP_PLUGIN_DIR . '/dompdf/autoload.inc.php';
        $options = new Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf\Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Télécharger le PDF
        $dompdf->stream(sanitize_title($title) . ".pdf", array("Attachment" => true));
        exit;
    }
}


?>