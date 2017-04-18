<!DOCTYPE html>
<html>
<body>

<p id="demo"></p>

<script>
try {
    alert("Sorry! Something went wrong :/");
}
catch(err) {
    document.getElementById("demo").innerHTML = err.message;
}
</script>

</body>
</html>
