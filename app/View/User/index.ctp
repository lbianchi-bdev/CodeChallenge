<h1>Users</h1>
<?php echo $this->Html->link('Add User', array('controller' => 'user', 'action' => 'add')); ?>
<table>
    <tr>
        <th>Id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Stripe Token</th>
    </tr>

    <!-- Here is where we loop through our array, printing out info -->

    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td><?php echo $user['User']['first_name']; ?></td>
        <td><?php echo $user['User']['last_name']; ?></td>
        <td><?php echo $user['User']['email']; ?></td>
        <td><?php echo $user['User']['stripeToken']; ?></td>
    </tr>
    <?php endforeach; ?>

</table>