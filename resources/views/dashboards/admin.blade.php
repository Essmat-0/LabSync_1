<div>
    <!-- It always seems impossible until it is done. - Nelson Mandela -->
</div>
<h1> YOU ARE ADMIN </h1>
<form method="POST" action="/deleteUser">
    @csrf
    <label>User ID</label>
    <input type="text" name="user_id" placeholder="USER_00" required>
    <button type="submit">DELETE USER</button>
</form>
