<?php
$user = \Illuminate\Support\Facades\Auth::user();
if (count($user->stores) > 0 && \Illuminate\Support\Facades\Session::get('store', null) == null) {
    \Illuminate\Support\Facades\Session::set('store', $user->stores[0]->id);
}
if (count($user->stores) > 1) {

    $current_store = \Illuminate\Support\Facades\Session::get('store', 0);
    ?>
    <div class="row">
        <div class="col-md-12">
            <label>Actieve winkel</label>
            <select class="form-control store-selection">
                <?php
                if ($current_store == 0) {
                    echo '<option value="0">Selecteer winkel</option>';
                }

                foreach ($user->stores as $store) {
                    echo "<option value='".$store->id."'";

                    if ($store->id == $current_store) {
                        echo " selected='selected' ";
                    }

                    echo ">".$store->display_title."</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}