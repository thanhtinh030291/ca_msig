<div class="table-responsive">
    <table class="table" id="reportGop-table">
        <thead>
            <tr>
                <th>Provider Name</th>
                <th>Tranfer Amt</th>
                <th>Tranfer Date</th>
                {{-- <th>Đã Hoàn Thành</th>
                <th>Chưa Hoan Thành</th> --}}
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reportGop as $reportGop)
            <tr>
                <td>{!! $reportGop->BEN_NAME !!}</td>
                <td class="text-danger font-weight-bold">{{ formatPrice($reportGop->AMT) }}</td>
                <td>{!! isset($reportGop->vnbt_sheets[0]) ? $reportGop->vnbt_sheets[0]->TF_DATE : " " !!}</td>
                {{-- <td></td>
                <td></td> --}}
                <td>
                    <a href="{!! route('reportGop.show', [$reportGop->VCBS_ID]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                    {{-- {!! Form::open(['route' => ['reportGop.destroy', $reportGop->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('reportGop.show', [$reportGop->id]) !!}" class='btn btn-success btn-xs'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('reportGop.edit', [$reportGop->id]) !!}" class='btn btn-primary btn-xs'><i class="fa fa-pencil-square-o"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!} --}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
