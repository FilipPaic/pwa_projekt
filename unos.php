<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unos Vijesti</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .bojaPoruke {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['korisnicko_ime']) || $_SESSION['razina'] != 1) {
        echo '<p>Nemate dovoljna prava za pristup ovoj stranici. <a href="login.php">Prijava</a></p>';
        exit();
    }
    ?>
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
            <h2>Unos Novih Vijesti</h2>
            <form name="unosVijesti" action="unos.php" method="POST" enctype="multipart/form-data">
                <div class="form-item">
                    <span id="porukaTitle" class="bojaPoruke"></span>
                    <label for="title">Naslov vijesti</label>
                    <input type="text" name="title" id="title" class="form-field-textual">
                </div>
                <div class="form-item">
                    <span id="porukaAbout" class="bojaPoruke"></span>
                    <label for="about">Kratki sadržaj vijesti (do 50 znakova)</label>
                    <textarea name="about" id="about" cols="30" rows="2" class="form-field-textual"></textarea>
                </div>
                <div class="form-item">
                    <span id="porukaContent" class="bojaPoruke"></span>
                    <label for="content">Sadržaj vijesti</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-field-textual"></textarea>
                </div>
                <div class="form-item">
                    <span id="porukaSlika" class="bojaPoruke"></span>
                    <label for="pphoto">Slika</label>
                    <input type="file" name="pphoto" id="pphoto" accept="image/*" class="input-text">
                </div>
                <div class="form-item">
                    <span id="porukaKategorija" class="bojaPoruke"></span>
                    <label for="category">Kategorija vijesti</label>
                    <select name="category" id="category" class="form-field-textual">
                        <option value="" disabled selected>Odabir kategorije</option>
                        <option value="sport">Sport</option>
                        <option value="kultura">Kultura</option>
                        <option value="politika">Politika</option>
                        <option value="tehnologija">Tehnologija</option>
                    </select>
                </div>
                <div class="form-item">
                    <label>Spremiti u arhivu</label>
                    <input type="checkbox" name="archive">
                </div>
                <div class="form-item">
                    <button type="reset">Poništi</button>
                    <button type="submit" id="slanje">Prihvati</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; TITANIA COMPAÑÍA EDITORIAL, S.L. 2019. España. Todos los derechos rezervados</p>
        <p>Condiciones | Política de Privacidad | Política de Cookies | Transparencia | Auditado por ComScore</p>
    </footer>

    <script type="text/javascript">
        document.getElementById("slanje").onclick = function(event) {
            var slanjeForme = true;

            var poljeTitle = document.getElementById("title");
            var title = poljeTitle.value;
            if (title.length < 5 || title.length > 30) {
                slanjeForme = false;
                poljeTitle.style.border="1px dashed red";
                document.getElementById("porukaTitle").innerHTML="Naslov vijesti mora imati između 5 i 30 znakova!<br>";
            } else {
                poljeTitle.style.border="1px solid green";
                document.getElementById("porukaTitle").innerHTML="";
            }

            var poljeAbout = document.getElementById("about");
            var about = poljeAbout.value;
            if (about.length < 10 || about.length > 100) {
                slanjeForme = false;
                poljeAbout.style.border="1px dashed red";
                document.getElementById("porukaAbout").innerHTML="Kratki sadržaj mora imati između 10 i 100 znakova!<br>";
            } else {
                poljeAbout.style.border="1px solid green";
                document.getElementById("porukaAbout").innerHTML="";
            }

            var poljeContent = document.getElementById("content");
            var content = poljeContent.value;
            if (content.length == 0) {
                slanjeForme = false;
                poljeContent.style.border="1px dashed red";
                document.getElementById("porukaContent").innerHTML="Sadržaj mora biti unesen!<br>";
            } else {
                poljeContent.style.border="1px solid green";
                document.getElementById("porukaContent").innerHTML="";
            }

            var poljeSlika = document.getElementById("pphoto");
            var pphoto = poljeSlika.value;
            if (pphoto.length == 0) {
                slanjeForme = false;
                poljeSlika.style.border="1px dashed red";
                document.getElementById("porukaSlika").innerHTML="Slika mora biti unesena!<br>";
            } else {
                poljeSlika.style.border="1px solid green";
                document.getElementById("porukaSlika").innerHTML="";
            }

            var poljeCategory = document.getElementById("category");
            if(poljeCategory.selectedIndex == 0) {
                slanjeForme = false;
                poljeCategory.style.border="1px dashed red";
                document.getElementById("porukaKategorija").innerHTML="Kategorija mora biti odabrana!<br>";
            } else {
                poljeCategory.style.border="1px solid green";
                document.getElementById("porukaKategorija").innerHTML="";
            }

            if (!slanjeForme) {
                event.preventDefault();
            }
        };
    </script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connect.php';

    $title = $_POST['title'];
    $about = $_POST['about'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $archive = isset($_POST['archive']) ? 1 : 0;
    $picture = $_FILES['pphoto']['name'];
    $target_dir = 'img/';
    $target_file = $target_dir . basename($picture);
    move_uploaded_file($_FILES["pphoto"]["tmp_name"], $target_file);

    $query = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva) VALUES (NOW(), ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'sssssi', $title, $about, $content, $picture, $category, $archive);
        mysqli_stmt_execute($stmt);
    }

    mysqli_close($conn);
}
?>
