<style>
    .tiny-icon {
        font-size:12px;
    }
</style>
<div class="ui left vertical inverted sidebar labeled icon menu">
  <a class="item" href="/home">
    <i class="users icon tiny-icon"></i>
    Subscribers
  </a>
</div>
<div class="row" style="padding:20px;background-color:#f2f2f2">
    <div class="column">
        <button class="menu-toggle-btn ui black button"><i class="bars icon"></i></button>
        <h4 style="color:gray;display:inline-flex;margin:0px;margin-left:10px">Welcome back! (<span style="color:cornflowerblue"><?php echo session('apikey'); ?></span>)</h4>
        <a href="/terminate" style="color:tomato;display:inline-flex;margin:0px;margin-left:10px;cursor:pointer">Exit -></a>
    </div>
</div>