<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctors List</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Doctors</h2>

        <a href="add.php" class="btn btn-primary">
            + Add Doctor
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Working Days</th>
                        <th style="width: 160px">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <!-- Backend loop goes here -->
                <!-- Example structure:
                <?php foreach ($doctors as $i => $doc): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($doc['name']) ?></td>
                        <td><?= htmlspecialchars($doc['email']) ?></td>
                        <td><?= htmlspecialchars($doc['specialization']) ?></td>
                        <td><?= htmlspecialchars($doc['days']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $doc['id'] ?>" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?= $doc['id'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this doctor?');"
                            >
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                -->
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
