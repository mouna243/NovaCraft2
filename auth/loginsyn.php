<?php

include __DIR__ . "/../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        echo "<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#c1cbff] text-[#2d50ff] border-[10px] rounded-[40px] p-[10px] text-[20px]'>Remplir tout les champs<i class='fa-regular fa-face-smile-beam'></i></p>";
        exit;
    } else {

        $email = $_POST["email"];
        $pw = $_POST["password"];
        $regex = "/^[\w.-]+@[\w]+\.(com|org|fr)$/";
        $r = preg_match($regex, $email);
        if ($r === 0) {
            echo "<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#c1cbff] text-[#2d50ff] border-[10px] rounded-[40px] p-[10px] text-[20px]'>entrer un email sous forme de \"exemple@email.com\"<i class='fa-regular fa-face-smile-beam'></i></p>";
            return;
        }
        //verification de l'email
        $requet = "SELECT email, password FROM users where email = ? ";
        $stmt = mysqli_prepare($conn, $requet);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);


        if ($row && password_verify($_POST["password"], $row["password"])) {
            $_SESSION["situ"] = true;
            header("Location: home");
            exit;
        } else {
            echo "<p id='flashmessage' class ='z-50 absolute top-1/4 bg-[#c1cbff] text-[#2d50ff] border-[10px] rounded-[40px] p-[10px] text-[20px]'>le email ou  bien le mot de pass est faut <i class='fa-regular fa-face-smile-beam'></i></p>";

        }
    }
} else {
    echo "<script>console.log('utilise la methode post');</script>";
}
?>
<script>
    const msg = document.getElementById("flashmessage");

    setTimeout(() => {
        msg.style.display = "none";
    }, 2000);
</script>