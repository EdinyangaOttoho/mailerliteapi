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
<div class="row" style="padding:10px;padding-left:20px;background-color:#f2f2f2">
    <div class="column">
        <button class="menu-toggle-btn ui black button"><i class="bars icon"></i></button>
    </div>
</div>
<script>
    document.body.oncontextmenu = function(event) {
        event.preventDefault();
        //Prevent Right clicks
    }
</script>