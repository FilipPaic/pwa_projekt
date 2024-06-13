<?php
session_start();
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracija</title>
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
                <li><a href="registracija.php">Registracija</a></li>
                <li><a href="login.php">Prijava</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        if (isset($_POST['prijava'])) {
            $prijavaImeKorisnika = $_POST['korisnicko_ime'];
            $prijavaLozinkaKorisnika = $_POST['lozinka'];

            $sql = "SELECT ime, prezime, korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime=?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $ime, $prezime, $korisnicko_ime, $hashed_password, $razina);
                mysqli_stmt_fetch($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($prijavaLozinkaKorisnika, $hashed_password)) {
                    $_SESSION['korisnicko_ime'] = $korisnicko_ime;
                    $_SESSION['razina'] = $razina;
                } else {
                    echo '<p>Neispravno korisničko ime ili lozinka. <a href="registracija.php">Registrirajte se</a></p>';
                }
            }
        }

        if (isset($_SESSION['korisnicko_ime']) && $_SESSION['razina'] == 1) {
            echo '<h2>Administracija</h2>';
            // Prikaz administracijskog sadržaja
            $query = "SELECT * FROM vijesti";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_array($result)) {
                echo '<form enctype="multipart/form-data" action="administrator.php" method="POST">';
                echo '<div class="form-item">';
                echo '<label for="title">Naslov vijesti:</label>';
                echo '<input type="text" name="title" class="form-field-textual" value="' . $row['naslov'] . '">';
                echo '</div>';
                echo '<div class="form-item">';
                echo '<label for="about">Kratki sadržaj vijesti (do 50 znakova):</label>';
                echo '<textarea name="about" cols="30" rows="2" class="form-field-textual">' . $row['sazetak'] . '</textarea>';
                echo '</div>';
                echo '<div class="form-item">';
                echo '<label for="content">Sadržaj vijesti:</label>';
                echo '<textarea name="content" cols="30" rows="10" class="form-field-textual">' . $row['tekst'] . '</textarea>';
                echo '</div>';
                echo '<div class="form-item">';
                echo '<label for="pphoto">Slika:</label>';
                echo '<input type="file" name="pphoto" accept="image/*" class="input-text">';
                echo '<img src="img/' . $row['slika'] . '" width=100px>';
                echo '</div>';
                echo '<div class="form-item">';
                echo '<label for="category">Kategorija vijesti:</label>';
                echo '<select name="category" class="form-field-textual">';
                echo '<option value="sport"' . ($row['kategorija'] == 'sport' ? ' selected' : '') . '>Sport</option>';
                echo '<option value="kultura"' . ($row['kategorija'] == 'kultura' ? ' selected' : '') . '>Kultura</option>';
                echo '<option value="politika"' . ($row['kategorija'] == 'politika' ? ' selected' : '') . '>Politika</option>';
                echo '<option value="tehnologija"' . ($row['kategorija'] == 'tehnologija' ? ' selected' : '') . '>Tehnologija</option>';
                echo '</select>';
                echo '</div>';
                echo '<div class="form-item">';
                echo '<label>Spremiti u arhivu:</label>';
                echo '<input type="checkbox" name="archive"' . ($row['arhiva'] == 1 ? ' checked' : '') . '/>';
                echo '</div>';
                echo '<div class="form-item">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<button type="reset">Poništi</button>';
                echo '<button type="submit" name="update">Izmjeni</button>';
                echo '<button type="submit" name="delete">Izbriši</button>';
                echo '</div>';
                echo '</form>';
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['delete'])) {
                    $id = $_POST['id'];
                    $query = "DELETE FROM vijesti WHERE id=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $query)) {
                        mysqli_stmt_bind_param($stmt, 'i', $id);
                        mysqli_stmt_execute($stmt);
                    }
                }

                if (isset($_POST['update'])) {
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $about = $_POST['about'];
                    $content = $_POST['content'];
                    $category = $_POST['category'];
                    $archive = isset($_POST['archive']) ? 1 : 0;
                    $picture = $_FILES['pphoto']['name'];
                    if ($picture != "") {
                        $target_dir = 'img/';
                        $target_file = $target_dir . basename($picture);
                        move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_file);
                        $query = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, slika=?, kategorija=?, arhiva=? WHERE id=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $query)) {
                            mysqli_stmt_bind_param($stmt, 'sssssii', $title, $about, $content, $picture, $category, $archive, $id);
                            mysqli_stmt_execute($stmt);
                        }
                    } else {
                        $query = "UPDATE vijesti SET naslov=?, sazetak=?, tekst=?, kategorija=?, arhiva=? WHERE id=?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $query)) {
                            mysqli_stmt_bind_param($stmt, 'ssssii', $title, $about, $content, $category, $archive, $id);
                            mysqli_stmt_execute($stmt);
                        }
                    }
                }
            }
            mysqli_close($conn);
        } else if (isset($_SESSION['korisnicko_ime']) && $_SESSION['razina'] != 1) {
            echo '<p>Bok ' . $_SESSION['korisnicko_ime'] . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
        } else {
            ?>
            <h2>Prijava</h2>
            <form action="administrator.php" method="POST">
                <div class="form-item">
                    <label for="korisnicko_ime">Korisničko ime:</label>
                    <input type="text" name="korisnicko_ime" id="korisnicko_ime" required>
                </div>
                <div class="form-item">
                    <label for="lozinka">Lozinka:</label>
                    <input type="password" name="lozinka" id="lozinka" required>
                </div>
                <div class="form-item">
                    <button type="submit" name="prijava">Prijava</button>
                </div>
            </form>
            <?php
        }
        ?>
    </main>

    <footer>
        <p>&copy; TITANIA COMPAÑÍA EDITORIAL, S.L. 2019. España. Todos los derechos rezervados</p>
        <p>Condiciones | Política de Privacidad | Política de Cookies | Transparencia | Auditado por ComScore</p>
    </footer>
</body>
</html>
