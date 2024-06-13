<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
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
        <section>
            <h2>Prijava</h2>
            <?php
            session_start();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include 'connect.php';
                $korisnicko_ime = $_POST['korisnicko_ime'];
                $lozinka = $_POST['lozinka'];

                $stmt = mysqli_stmt_init($dbc);
                $sql = "SELECT ime, prezime, korisnicko_ime, lozinka, razina FROM korisnik WHERE korisnicko_ime=?";
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, 's', $korisnicko_ime);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $ime, $prezime, $korisnicko_ime, $hashed_password, $razina);
                    mysqli_stmt_fetch($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($lozinka, $hashed_password)) {
                        $_SESSION['korisnicko_ime'] = $korisnicko_ime;
                        $_SESSION['razina'] = $razina;
                        if ($razina == 1) {
                            header("Location: administrator.php");
                            exit();
                        } else {
                            echo "<p>Bok $ime! Uspješno ste prijavljeni, ali niste administrator.</p>";
                        }
                    } else {
                        echo '<p>Neispravno korisničko ime ili lozinka. <a href="registracija.php">Registrirajte se</a></p>';
                    }
                }
                mysqli_close($dbc);
            } else {
                ?>
                <form action="login.php" method="POST">
                    <div class="form-item">
                        <label for="korisnicko_ime">Korisničko ime:</label>
                        <input type="text" name="korisnicko_ime" id="korisnicko_ime" required>
                    </div>
                    <div class="form-item">
                        <label for="lozinka">Lozinka:</label>
                        <input type="password" name="lozinka" id="lozinka" required>
                    </div>
                    <div class="form-item">
                        <button type="submit">Prijava</button>
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
