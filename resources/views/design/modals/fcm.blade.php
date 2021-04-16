<html>
<title>Firebase Messaging Demo</title>
<style>
    div {
        margin-bottom: 15px;
    }
</style>
<body>
    <div id="token"></div>
    <div id="msg"></div>
    <div id="notis"></div>
    <div id="err"></div>
    <script>
       MsgElem = document.getElementById("msg")
       TokenElem = document.getElementById("token")
       NotisElem = document.getElementById("notis")
       ErrElem = document.getElementById("err")
    </script>
</body>
<script>
    var config = {
        messagingSenderId: "193162804196",
        apiKey: "AIzaSyBvFM0v-DCmxGBYwVU-Fi6r_rUkQRBi57U",
        projectId: "drazamedapp",
        appId: "1:193162804196:web:5514e23878a8fb473425f1"
    };
    firebase.initializeApp(config);
</script>
</html>

