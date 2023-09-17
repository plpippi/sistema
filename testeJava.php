<html>
    <body>
        <form id="formulario" method="POST" action="php.php">
            <input type="hidden" id="recebe" name="recebe" value="">
        </form>
        <button type="button" onclick="document.getElementById('recebe').value = '1'; document.getElementById('formulario').submit();">envia</button>
    </body>
</html>