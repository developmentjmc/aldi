@php
    $deep = $menu->deep + 2;

    if (!AuthHelper::menuAccess($menu->id, 'read')) {
        return;
    }
@endphp

<?php if (empty($menu->tree)) { ?>
<li class="nav-item">
    <a href="{{ $menu->link() }}" class="nav-link">
        @if (isset($menu->icon))
            <i class="<?= $menu->icon ?>"></i>
            &nbsp;
        @endif
        {{ $menu->name }}
    </a>
</li>

<?php } else { ?>
<li class="nav-item dropdown">
    <a href="{{ $menu->link() }}" data-bs-toggle="dropdown" class="nav-link dropdown-toggle">
        @if (isset($menu->icon))
            <i class="<?= $menu->icon ?>"></i>
            &nbsp;
        @endif
        {{ $menu->name }}
    </a>

    <ul class="dropdown-menu">
        <?php foreach ($menu->tree as $menu) { ?>
        @include('backend/layouts/main_sidebar_entry', get_defined_vars())
        <?php } ?>
    </ul>
</li>
<?php } ?>
