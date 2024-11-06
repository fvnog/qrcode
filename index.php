<?php
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Logo\Logo;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'generate') {
        $link = $_POST['link'];
        $color = isset($_POST['color']) ? $_POST['color'] : '#000000';
        $bgcolor = isset($_POST['bgcolor']) ? $_POST['bgcolor'] : '#ffffff';
        $logoPath = null;

        // Handle file upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['logo']['tmp_name'];
            $fileName = $_FILES['logo']['name'];
            $fileSize = $_FILES['logo']['size'];
            $fileType = $_FILES['logo']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = './uploaded_logos/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $logoPath = $dest_path;
                }
            }
        }

        $qrCode = new QrCode($link);
        $qrCode->setSize(300);
        $qrCode->setForegroundColor(new Color(hexdec(substr($color, 1, 2)), hexdec(substr($color, 3, 2)), hexdec(substr($color, 5, 2))));
        $qrCode->setBackgroundColor(new Color(hexdec(substr($bgcolor, 1, 2)), hexdec(substr($bgcolor, 3, 2)), hexdec(substr($bgcolor, 5, 2))));

        if ($logoPath) {
            $qrCode->setLogo(new Logo($logoPath, 50));
        }

        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);

        if (!isset($_SESSION['qrcodes'])) {
            $_SESSION['qrcodes'] = [];
        }

        $_SESSION['qrcodes'][] = [
            'link' => $link,
            'image' => base64_encode($qrCodeImage->getString())
        ];

        $response = [
            'mimeType' => $qrCodeImage->getMimeType(),
            'base64' => base64_encode($qrCodeImage->getString())
        ];
        echo json_encode($response);
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $index = $_POST['index'];
        if (isset($_SESSION['qrcodes'][$index])) {
            unset($_SESSION['qrcodes'][$index]);
            $_SESSION['qrcodes'] = array_values($_SESSION['qrcodes']); // Reindex array
        }
        echo json_encode(['status' => 'success']);
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_all') {
        unset($_SESSION['qrcodes']);
        echo json_encode(['status' => 'success']);
        exit();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gere QR Codes de forma rápida e fácil com o Gerador de QR Code criado por Fernando Nogueira. Ferramenta gratuita, intuitiva e eficiente para todas as suas necessidades.">
    <meta name="keywords" content="gerador de QR Code, QR Code, criar QR Code, QR Code gratuito, Fernando Nogueira, ferramenta de QR Code">
    <meta name="author" content="Fernando Nogueira">
    <meta property="og:title" content="Gerador de QR Code - Fernando Nogueira">
    <meta property="og:description" content="Gere QR Codes de forma rápida e fácil com o Gerador de QR Code criado por Fernando Nogueira. Ferramenta gratuita, intuitiva e eficiente para todas as suas necessidades.">
    <meta property="og:image" content="URL_da_imagem_de_previsao">
    <meta property="og:url" content="URL_da_pagina">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Gerador de QR Code - Fernando Nogueira">
    <meta name="twitter:description" content="Gere QR Codes de forma rápida e fácil com o Gerador de QR Code criado por Fernando Nogueira. Ferramenta gratuita, intuitiva e eficiente para todas as suas necessidades.">
    <meta name="twitter:image" content="URL_da_imagem_de_previsao">

    <title>Gerador de QR Code - Fernando Nogueira</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

<section class="bg-new shadow">
<div class="container pt-5 pb-5">

<div class="text-center">
    <a href="https://fvnog.github.io/">
        <img src="logo.png" alt="Fernando Nogueira" class="logo">
    </a>
</div>

<h1 class="text-center mb-4 tx fw-bold">GERADOR DE QR Code</h1>
<form id="qrForm" class="row g-3 justify-content-center" enctype="multipart/form-data">
<div class="row justify-content-center align-items-center text-center mt-5">
    <div class="col-md-4">
        <label for="link" class="form-label">Digite o link</label>
        <input type="text" class="form-control" id="link" name="link" placeholder="Digite o link" required>
    </div>
</div>

<div class="row justify-content-center align-items-center text-center mt-3 mb-5">
    <div class="col-md-2 mt-2">
        <label for="color" class="form-label">Cor do QR Code</label>
        <input type="color" class="form-control" id="color" name="color" value="#000000" title="Cor do QR Code">
    </div>
    <div class="col-md-2 mt-2">
        <label for="bgcolor" class="form-label">Cor de Fundo</label>
        <input type="color" class="form-control" id="bgcolor" name="bgcolor" value="#ffffff" title="Cor de Fundo">
    </div>
</div>


    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-qrcode"></i> Gerar QR Code</button>
    </div>
</form>
<div id="qrCodeResult" class="text-center mt-4"></div>

        </div>
</section>

<div class="container mt-5 mb-5">
    <div id="savedQrCodes" class="mt-5">
        <h2 class="text-center mb-4 text">QR Codes Salvos</h2>

        <table class="table table-bordered text-center responsive rounded shadow">
            <thead class="table-dark">
                <tr>
                    <th scope="col">QR Code</th>
                    <th scope="col">Link</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($_SESSION['qrcodes'])): ?>
                    <?php foreach ($_SESSION['qrcodes'] as $index => $qr): ?>
                        <tr data-index="<?= $index ?>">
                            <td><img src="data:image/png;base64,<?= $qr['image'] ?>" alt="QR Code" class="img-fluid" style="max-width: 50px;"></td>
                            <td class="pt-3"><?= htmlspecialchars($qr['link']) ?></td>
                            <td>
                                <a href="data:image/png;base64,<?= $qr['image'] ?>" download="qrcode.png" class="btn btn-primary"><i class="fas fa-download"></i></a>
                                <button class="btn btn-danger deleteQrCode mr-3"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-end mt-3 mb-3">
            <button id="clearAll" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Limpar Todos</button>
        </div>

    </div>
</div>


<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p class="mb-2">Gerador de QR Code com opção de criar QR Codes personalizados com cores, desenvolvido por <strong>Fernando Nogueira</strong>, totalmente gratuito.</p>
        <p class="mb-0">Para mais informações, visite nosso site <a href="https://fvnog.github.io/" class="text-white"><u>Portfólio</u></a>.</p>
    </div>
</footer>


<script>
 $(document).ready(function() {
    $('#qrForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'generate');

        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var data = JSON.parse(response);
                var imgSrc = 'data:' + data.mimeType + ';base64,' + data.base64;
                var qrCodeHtml = '<h2>QR Code Gerado:</h2>';
                qrCodeHtml += '<img src="' + imgSrc + '" alt="QR Code" class="img-fluid"></br>';
                qrCodeHtml += '<a href="' + imgSrc + '" download="qrcode.png" class="btn btn-primary mt-3"><i class="fas fa-download"></i> Download QR Code</a>';
                $('#qrCodeResult').html(qrCodeHtml);

                var newRow = '<tr>';
                newRow += '<td><img src="' + imgSrc + '" alt="QR Code" class="img-fluid" style="max-width: 50px;"></td>';
                newRow += '<td class="pt-3">' + $('#link').val() + '</td>';
                newRow += '<td><a href="' + imgSrc + '" download="qrcode.png" class="btn btn-primary"><i class="fas fa-download"></i></a>';
                newRow += '<button class="btn btn-danger deleteQrCode mr-3"><i class="fas fa-trash"></i></button></td>';
                newRow += '</tr>';
                $('table tbody').append(newRow);
            }
        });
    });

    $(document).on('click', '.deleteQrCode', function() {
        var row = $(this).closest('tr');
        var index = row.data('index');

        $.ajax({
            url: '',
            type: 'POST',
            data: { action: 'delete', index: index },
            success: function(response) {
                row.remove();
            }
        });
    });

    $('#clearAll').on('click', function() {
        $.ajax({
            url: '',
            type: 'POST',
            data: { action: 'delete_all' },
            success: function(response) {
                $('table tbody').empty();
            }
        });
    });
});


</script>
</body>
</html>
