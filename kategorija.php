<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>El Confidencial</h1>
        <p>EL DIARIO DE LOS LECTORES INFLUYENTES</p>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="unos.php">Unos Vijesti</a></li>
                <li><a href="administrator.php">Administracija</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <?php
            include 'connect.php';
            $kategorija = $_GET['id'];
            echo "<h2>$kategorija</h2>";
            $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='$kategorija'";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo '<article>';
                echo '<img src="img/' . $row['slika'] . '" alt="' . $row['naslov'] . '">';
                echo '<h3><a href="clanak.php?id=' . $row['id'] . '">' . $row['naslov'] . '</a></h3>';
                echo '<p>' . $row['sazetak'] . '</p>';
                echo '</article>';
            }
            mysqli_close($dbc);
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; TITANIA COMPAÑÍA EDITORIAL, S.L. 2019. España. Todos los derechos rezervados</p>
        <p>Condiciones | Política de Privacidad | Política de Cookies | Transparencia | Auditado por ComScore</p>
    </footer>
</body>
</html>
