<?php
include __DIR__ . "/../config/database.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
        echo "<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#c1cbff] text-[#2d50ff] border-[10px] rounded-[40px] p-[10px] text-[20px]'>Remplir tout les champs<i class='fa-regular fa-face-smile-beam'></i></p>";
        return;
    } else {

        $user_name = $_POST["name"];
        $email = $_POST["email"];
        $pw = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $regex = "/^[\w.-]+@[\w]+\.(com|fr|org)$/";
        $emailcheck = preg_match($regex, $email);
        if ($emailcheck === 0) {
            echo "<p id='flashmessage' class ='z-50  absolute top-1/4 bg-[#fab897] text-[#650f00] border-[10px] rounded-[40px] p-[10px] text-[20px]'>respecter cette écriture \"exemple@gmail.com\" </p>";
            return;
        }
        if (strlen($_POST["password"]) < 6) {
            echo "<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#ffaf87] text-[#650f00] border-[10px] rounded-[40px] p-[10px] text-[20px]'>enter un password plus que 6 caratères </p>";
            return;
        }

        //Verifier si l'email existe
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            die("<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#ffccb2] text-[#d54e06] border-[10px] rounded-[40px] p-[10px] text-[20px]'>ce user a été déja existe <i class='fa-solid fa-triangle-exclamation'></i></p>");
        }

        // entrer le nouveau utilisateur
        $insert_sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt, "sss", $user_name, $email, $pw);

        if (mysqli_stmt_execute($stmt)) {
            echo "<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#c1cbff] text-[#2d50ff] border-[10px] rounded-[40px] p-[10px] text-[20px]'>User ajouté avec succès<i class='fa-regular fa-face-smile-beam'></i></p>";
            // header("Location: login");

        } else {
            echo "❌ Erreur : " . mysqli_error($conn);
        }

    }
} else {
    echo "<script> console.log('get methode');  </script>";
}



?>

<script>
    const msg = document.getElementById("flashmessage");

    setTimeout(() => {
        msg.style.display = "none";
    }, 2000);
</script>