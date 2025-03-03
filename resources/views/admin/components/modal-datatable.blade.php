<?php
/**
 * @param string $id - ID модального окна
 * @param string $title - Заголовок модального окна
 * @param array $columns - Массив колонок ['key' => 'Название']
 */
?>
<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table w-100" id="{{ $id }}_table">
                    <thead>
                        <tr>
                            @foreach($columns as $key => $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
