<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
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
            </ul>
        </nav>
    </header>
    
    <main>
        <section>
            <h2>Registracija</h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include 'connect.php';
                $ime = $_POST['ime'];
                $prezime = $_POST['prezime'];
                $korisnicko_ime = $_POST['korisnicko_ime'];
                $lozinka = $_POST['lozinka'];
                $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);
                $razina = 0;

                // Provjera postoji li korisnik
                $stmt = mysqli_stmt_init($dbc);
                $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime=?";
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, 's', $korisnicko_ime);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                }

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    echo '<p>Korisničko ime već postoji!</p>';
                } else {
                    // Unos novog korisnika
                    $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, 'ssssd', $ime, $prezime, $korisnicko_ime, $hashed_password, $razina);
                        mysqli_stmt_execute($stmt);
                        echo '<p>Korisnik je uspješno registriran!</p>';
                    }
                }
                mysqli_close($dbc);
            } else {
                ?>
                <form action="registracija.php" method="POST">
                    <div class="form-item">
                        <label for="ime">Ime:</label>
                        <input type="text" name="ime" id="ime" required>
                    </div>
                    <div class="form-item">
                        <label for="prezime">Prezime:</label>
                        <input type="text" name="prezime" id="prezime" required>
                    </div>
                    <div class="form-item">
                        <label for="korisnicko_ime">Korisničko ime:</label>
                        <input type="text" name="korisnicko_ime" id="korisnicko_ime" required>
                    </div>
                    <div class="form-item">
                        <label for="lozinka">Lozinka:</label>
                        <input type="password" name="lozinka" id="lozinka" required>
                    </div>
                    <div class="form-item">
                        <label for="lozinka2">Ponovite lozinku:</label>
                        <input type="password" name="lozinka2" id="lozinka2" required>
                    </div>
                    <div class="form-item">
                        <button type="submit">Registracija</button>
                    </div>
                </form>
                <?php
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; TITANIA COMPAÑÍA EDITORIAL, S.L. 2019. España. Todos los derechos rezervados</p>
        <p>Condiciones | Política de Privacidad | Política de Cookies | Transparencia | Auditado por ComScore</p>
    </footer>
</body>
</html>
