<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <form method="GET">
  <select name="group">
    <option value="">All Groups</option>
    <option value="BCSIT">BCSIT</option>
    <option value="BBA">BBA</option>
  </select>
  <button type="submit">Filter</button>
</form>

</body>
</html>