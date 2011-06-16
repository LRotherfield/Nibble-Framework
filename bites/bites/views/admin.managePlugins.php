<section>
  <header>
    <h1>Bites (Nibble plugins)</h1>
  </header>
  <p>
    In this area you can awaken and put to sleep(activate and deactivate) bites.
    Below are 2 columns, you can move yellow bites between awake and asleep columns to change their state.
    Red Bites cannot be moved as they are nessecary for this Nibble install to function.
  </p>
  <ul id="sortable1" class="droptrue rounded selector" rel="awake">
    <li class="disabled heading">
      <h2>Awake Bites</h2>
      <p>
        Each Bite in the following table is awake, this means that
        this application is making use of these Bites. Drag them
        into the asleep Bites column to send them back to sleep.
      </p>
    </li>
    <? foreach ($awake_bites as $bite): ?>
      <li class="rounded <? if ($bite['id'] == 1)
        echo 'disabled'; ?>">
        <input type="hidden" value="<?= $bite['id'] ?>" />
        <h3 title="<?= $bite['desc'] ?>"><?= ucfirst($bite['name']) ?></h3>
      </li>
    <? endforeach; ?>
    </ul>
    <ul id="sortable2" class="droptrue rounded selector" rel="asleep">
      <li class="disabled heading">
        <h2>Asleep Bites</h2>
        <p>
          These precious little Bites are sleeping, this means they are not
          being used by your application.  Drag them to the awake Bites
          column to wake them up.
        </p>
      </li>
<? foreach ($asleep_bites as $bite): ?>
        <li class="rounded">
          <input type="hidden" value="<?= $bite['id'] ?>" />
          <h3 title="<?= $bite['desc'] ?>"><?= ucfirst($bite['name']) ?></h3>
        </li>
<? endforeach; ?>
  </ul>


</section>