<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Confidencial</title>
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
        <section class="sport">
            <h2>Sport</h2>
            <?php
            include 'connect.php';
            $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='sport' LIMIT 4";
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
        <section class="kultura">
            <h2>Kultura</h2>
            <?php
            include 'connect.php';
            $query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='kultura' LIMIT 4";
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
