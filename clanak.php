<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Članak</title>
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
            $id = $_GET['id'];
            $query = "SELECT * FROM vijesti WHERE id=$id";
            $result = mysqli_query($dbc, $query);
            $row = mysqli_fetch_array($result);
            echo '<h2>' . $row['kategorija'] . '</h2>';
            echo '<h1>' . $row['naslov'] . '</h1>';
            echo '<p>AUTOR:</p>';
            echo '<p>OBJAVLJENO: ' . $row['datum'] . '</p>';
            echo '<img src="img/' . $row['slika'] . '" alt="' . $row['naslov'] . '">';
            echo '<p>' . $row['sazetak'] . '</p>';
            echo '<p>' . $row['tekst'] . '</p>';
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
