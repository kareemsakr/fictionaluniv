<div class="generic-content">
    <form class="search-form" method="get" action="<?= esc_url(site_url('/')) ?>">
        <label class="headline headline--medium" for="s">Search</label>
        <div class="search-form-row">
            <input placeholder="What are you looking for" class="s" type="search" name="s" id="s">
            <input class="search-submit" type="submit" value="search"></input>
        </div>
    </form>
</div>