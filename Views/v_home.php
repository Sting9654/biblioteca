<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Views\Public\css\bootstrap.min.css">
    <title>inicio</title>
</head>

<body>
    <section id="carouselSection" class="justify-content-center" style="margin-top:1%; margin-right:10%; margin-left:10%">
        <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="Views/Assets/Banners/openart-image_Dny-GMQ0_1726984147421_raw.png" class="d-block w-100" height="500">
                </div>
                <div class="carousel-item">
                    <img src="Views/Assets/Banners/openart-image_eYaUv8YI_1726984147307_raw.png" class="d-block w-100" height="500">
                </div>
                <div class="carousel-item">
                    <img src="Views/Assets/Banners/openart-image_wAr5teAD_1726983746982_raw.png" class="d-block w-100" height="500">
                </div>
                <div class="carousel-item">
                    <img src="Views/Assets/Banners/openart-image_wuzbttPz_1726983747030_raw.png" class="d-block w-100" height="500">
                </div>
            </div>
        </div>
    </section>

    <section aria-label="news-products" class="m-5 p-4">
        <h2 class="pb-5">Nuevos Agregados</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php if (isset($libros) && is_array($libros) && count($libros) > 0): ?>
                <?php foreach ($libros as $row): ?>
                    <div class="col">
                        <div class="card h-100 shadow">
                            <img style="object-fit:contain;" height="250px" width="100%" src="<?php echo $row['LIB_IMG_URL'] === "" ? 'Views/Assets/no-img-found.png' : $row['LIB_IMG_URL']; ?>" class="card-img-top p-3">
                            <div class="card-body p-3">
                                <h5 class="card-title"><?= htmlspecialchars($row['LIB_TITULO']); ?></h5><br>
                                <p class="card-text">Autor: <?= htmlspecialchars($row['LIB_AUTOR']);
                                                            ?></p><br>
                                <p class="card-text"><?= ucfirst(htmlspecialchars($row['LIB_SIPNOPSIS'])); ?></p>

                                <a href="index.php?controller=Libro&action=show&id=<?= htmlspecialchars($row['LIB_ID']); ?>" class="btn btn-outline-primary">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Pronto se añadirán nuevos productos</p>
            <?php endif; ?>
        </div>
    </section>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Biblioteca. Todos los derechos reservados.</p>
        <p>
            <a href="#" class="text-white">Política de Privacidad</a> |
            <a href="#" class="text-white">Términos de Servicio</a>
        </p>
    </footer>

    <script src="Views/Public/js/bootstrap.bundle.min.js"></script>
</body>

</html>
</body>

</html>