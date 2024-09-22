<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container px-0">
        <a class="navbar-brand ms-0" href="index.php">
            <img style="border-radius: 20%;" width="65px" height="65px" src="<?= htmlspecialchars($empresa['logo']) ?>" alt="">
            <?= htmlspecialchars($empresa['name']) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                        Libros
                    </a>
                    <ul class="dropdown-menu bg-dark">
                        <li><a class="dropdown-item text-light" href="index.php?controller=Client&action=index">Lista de Libros</a></li>
                        <li><a class="dropdown-item text-light" href="index.php?controller=Client&action=createForm">Registrar Libro</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
