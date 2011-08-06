<div class="miniPanel">
    <ul>
        <li>
<!--            <a href="--><?//=$this->url("/users/edit");?><!--"><h2>Edit Profile</h2></a>-->
            <a href="javascript:void(0);" onclick="showWindow('users/editProfile');"><h2>Edit Profile</h2></a>
        </li>
        <li>
            <a href="<?=$this->url("/users/messages");?>"><h2>Messages</h2></a>
        </li>
        <li>
            <a href="#" onclick="initModalPassword(); letsChangePassword();"><h2>Change Password</h2></a>
        </li>
    </ul>
<!--    <a href="--><?//=$this->url('/users/dashboard');?><!--">Profile</a>&nbsp;&nbsp;&bull;&nbsp;-->
<!--        <a href="--><?//=(Session::read('fb_logout_url'))?:$this->url('users/logout');?><!--">Logout</a>-->
</div>
<style type="text/css">
    .miniPanel ul { margin:0 !important; padding:0 !important; }
    .miniPanel h2 { margin:0; font-size: 1.1em; display: inline-block; color: #777777; }
    .miniPanel ul li {
        float: left;
        list-style: none;
        display: inline-block;
        margin-right: 10px;
    }
</style>