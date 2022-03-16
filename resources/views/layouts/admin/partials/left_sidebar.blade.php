<!-- Stored in resources/views/layouts/admin/partials/left_sidebar.blade.php -->
<div class="left main-sidebar">
    <div class="sidebar-inner leftscroll">
        <div id="sidebar-menu">
            <ul>
                <li class="submenu">
                    <a  class="{{ setActive('home') }}"
                    href="{{ url('admin/home')}}"><i class="fa fa-fw fa-home"></i><span> {{ __('message.home')}} </span> </a>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-fw fa-ticket"></i> <span> {{__('message.claim_management')}} </span>
                    </a>
                    <ul class="list-unstyled">
                        @hasanyrole('Header|Manager|Admin|Claim|Claim Independent|Lead|QC|Supper|view|AdminClaim')
                        <li class="{{ setActive('admin/claim') }}"> 
                            <a class="{{ setActive('admin/claim') }}"
                            href="{{ url('admin/claim') }}"><span> {{ __('message.form_claim_M')}}</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Header|ManagerGOP|Admin|ClaimGOP|AdminClaim|Supper|view')
                        <li class="{{ setActive('admin/claim') }}"> 
                            <a class="{{ setActive('admin/claim') }}"
                            href="{{ url('admin/P/claim') }}"><span> {{ __('message.form_claim_P')}}</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Header|ManagerGOP|Admin|Manager')
                        <li class="{{ setActive('admin/claimExport') }}"> 
                            <a class="{{ setActive('admin/claimExport') }}"
                            href="{{ url('admin/claimExport') }}"><span>Report Default</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Header|ManagerGOP|Admin|AdminClaim|view|Supper')
                        <li class="{{ setActive('admin/reportAdmins') }}"> 
                            <a class="{{ setActive('admin/reportAdmins') }}"
                            href="{{ url('admin/reportAdmins') }}"><span> Report for Admin Claim</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Header|ManagerGOP|Admin|ClaimGOP|QC|view|Supper')
                        <li class="{{ setActive('admin/reportGop') }}"> 
                            <a class="{{ setActive('admin/reportGop') }}"
                            href="{{ url('admin/reportGop') }}"><span> Report for GOP Claim</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Header|ManagerGOP|Admin|Lead|Manager|Supper|QC') 
                        <li class="{{ setActive('admin/reason_reject') }}"> 
                            <a class="{{ setActive('admin/reason_reject') }}"
                            href="{{ url('admin/reason_reject') }}"><span> {{ __('message.reason_reject')}}</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Header|Manager|Admin|ManagerGOP|Supper|QC')
                        <li class="{{ setActive('admin/product') }}"> 
                            <a class="{{ setActive('admin/product') }}"
                            href="{{ url('admin/product') }}"><span> {{ __('message.product')}}</span></a>
                        </li>
                        <li class="{{ setActive('admin/term') }}"> 
                            <a class="{{ setActive('admin/term') }}"
                            href="{{ url('admin/term') }}"><span> {{ __('message.term')}}</span></a>
                        </li>
                        <li class="{{ setActive('admin/letter_template') }}"> 
                            <a class="{{ setActive('admin/letter_template') }}"
                            href="{{ url('admin/letter_template') }}"><span> {{ __('message.letter_template')}}</span></a>
                        </li>
                        <li class="{{ setActive('admin/pocy') }}"> 
                            <a class="{{ setActive('admin/pocy') }}"
                            href="{{ url('admin/pocy') }}"><span> Policy Management</span></a>
                        </li>
                        @endhasanyrole
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-envelope-square" aria-hidden="true"></i> <span> EMail Management </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/mailbox') }}"> 
                            <a class="{{ setActive('admin/mailbox') }}"
                            href="{{ url('admin/mailbox') }}"><span>Index</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span> Payment Management </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/PaymentHistory') }}"> 
                            <a class="{{ setActive('admin/PaymentHistory') }}"
                            href="{{ url('admin/PaymentHistory') }}"><span>PaymentHistory</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="{{ url('admin/get_renewed_claim') }}">
                        <i class="fa fa-retweet text-danger" aria-hidden="true"></i> <span> Finance Return Claim </span>
                    </a>
                </li>
                {{-- UNC Sign --}}
                @hasanyrole('Header|Manager|Admin|ManagerGOP')
                {{-- <li class="submenu">
                    <a href="#">
                        <i class="fa fa-pencil-square" aria-hidden="true"></i> <span> Sign UNC </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/uncSign') }}"> 
                            <a class="{{ setActive('admin/uncSign') }}"
                            href="{{ url('admin/uncSign') }}"><span> Sign UNC</span></a>
                        </li>
                    </ul>
                </li> --}}
                @endhasanyrole
                @hasanyrole('Header|Admin|ManagerGOP')
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-hospital-o" aria-hidden="true"></i> <span> Provide Management </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/providers') }}"> 
                            <a class="{{ setActive('admin/providers') }}"
                            href="{{ url('admin/providers') }}"><span>Provide Info</span></a>
                        </li>
                    </ul>
                </li>
                @endhasanyrole
                
                {{-- Medical --}}
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-medkit" aria-hidden="true"></i> <span> Medical Management </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/claimWordSheets') }}"> 
                            <a class="{{ setActive('admin/claimWordSheets') }}"
                            href="{{ url('admin/claimWordSheets') }}"><span> Q&A Of Claim</span></a>
                        </li>
                    </ul>
                </li>
                {{-- Support Tool --}}
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-calculator" aria-hidden="true"></i> <span> Support Tool </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/roomAndBoards') }}"> 
                            <a class="{{ setActive('admin/roomAndBoards') }}"
                            href="{{ url('admin/roomAndBoards') }}"><span> Room & Board</span></a>
                        </li>
                    </ul>
                </li>
                @role('Admin')
                <li class="submenu">
                    <a href="#">
                        <i class="fa fa-cogs" aria-hidden="true"></i> <span> System Management </span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ setActive('admin/importExportView') }}"> 
                            <a class="{{ setActive('admin/importExportView') }}"
                            href="{{ url('admin/importExportView') }}"><span> Import & Export  DB</span></a>
                        </li>
                        <li class="{{ setActive('admin/admins') }}">
                            <a  class="{{ setActive('admin/admins') }}" href="{{ url('admin/admins') }}">
                            <span> {{ __('message.staff_management')}}</span></a>
                        </li>
                        <li class="{{ setActive('admin/role') }}">
                            <a  class="{{ setActive('admin/role') }}" href="{{ url('admin/role') }}">
                            <span> {{ __('message.role_management')}}</span></a>
                        </li>
                        <li class="{{ setActive('admin/permiss') }}">
                            <a  class="{{ setActive('admin/permiss') }}" href="{{ url('admin/permiss') }}">
                            <span> Permission Managerment</span></a>
                        </li>
                        <li class="{{ setActive('admin/roleChangeStatuses') }}">
                            <a  class="{{ setActive('admin/roleChangeStatuses') }}" href="{{ url('admin/roleChangeStatuses') }}">
                            <span> List Status Approved</span></a>
                        </li>
                        <li class="{{ setActive('admin/levelRoleStatuses') }}">
                            <a  class="{{ setActive('admin/levelRoleStatuses') }}" href="{{ url('admin/levelRoleStatuses') }}">
                            <span> List Level Approved</span></a>
                        </li>
                        <li class="{{ setActive('admin/transactionRoleStatuses') }}">
                            <a  class="{{ setActive('admin/transactionRoleStatuses') }}" href="{{ url('admin/transactionRoleStatuses') }}">
                            <span> Transaction Approved</span></a>
                        </li>

                        <li class="{{ setActive('admin/groupUsers') }}">
                            <a  class="{{ setActive('admin/groupUsers') }}" href="{{ url('admin/groupUsers') }}">
                            <span> Group of Users</span></a>
                        </li>
                        
                        <li class="{{ setActive('admin/setting') }}">
                            <a  class="{{ setActive('admin/setting') }}" href="{{ url('admin/setting') }}">
                            <span> Setting App</span></a>
                        </li>

                    </ul>
                </li>
                @endrole
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
