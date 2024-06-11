<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prikaz Vijesti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>El Confidencial</h1>
        <p>EL DIARIO DE LOS LECTORES INFLUYENTES</p>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="#">Europa</a></li>
                <li><a href="#">Teknautas</a></li>
                <li><a href="#">Administracija</a></li>
                <li><a href="unos.html">Unos Vijesti</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Prikaz Unesene Vijesti</h2>
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $title = htmlspecialchars($_POST['title']);
                    $about = htmlspecialchars($_POST['about']);
                    $content = htmlspecialchars($_POST['content']);
                    $category = htmlspecialchars($_POST['category']);
                    $archive = isset($_POST['archive']) ? 'Da' : 'Ne';

                    $image = $_FILES['pphoto']['name'];
                    $target_dir = "uploads/";
                    $target_file = $target_dir . basename($image);
                    move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_file);

                    echo "<p><strong>Kategorija:</strong> $category</p>";
                    echo "<h1>$title</h1>";
                    echo "<p><strong>Kratki sadržaj:</strong> $about</p>";
                    echo "<p><strong>Spremiti u arhivu:</strong> $archive</p>";
                    echo "<div><img src='$target_file' alt='$title'></div>";
                    echo "<p>$content</p>";
                } else {
                    echo "<p>Nema podataka za prikaz.</p>";
                }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; TITANIA COMPAÑÍA EDITORIAL, S.L. 2019. España. Todos los derechos reservados</p>
        <p>Condiciones | Política de Privacidad | Política de Cookies | Transparencia | Auditado por ComScore</p>
    </footer>
</body>
</html>
