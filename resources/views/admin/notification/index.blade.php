@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <form action="{{ route('admin.customerNotification.send') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header bg-custom">
                    <h4 class="card-title m-0 py-2">
                        <i class="bi bi-bell"></i> {{ __('Send Notification') }}
                    </h4>
                </div>
                <div class="card-body">

                    <x-input name="title" type="text" label="Title" placeholder="Notification Title" required="true" />

                    <div class="mt-3">
                        <label class="mb-1">
                            {{ __('Message') }}
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="message" class="form-control" rows="4" placeholder="{{ __('Notification Message...') }}">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary py-2 px-4">
                            {{ __('Send Message') }}
                        </button>
                    </div>
                </div>
            </div>


            <div class="card mt-3">
                <div class="card-body">

                    <div class="d-flex justify-content-start align-items-end flex-wrap mb-3" style="gap: 10px">
                        <div style="width: 200px">
                            <label class="font-weight-normal font-14 m-0">
                                {{ __('Filter by Device Type') }}
                            </label>
                            <select id="deviceType" class="form-control">
                                <option value="all">
                                    {{ __('All') }}
                                </option>
                                <option value="android">
                                    {{ __('Android') }}
                                </option>
                                <option value="ios">
                                    {{ __('IOS') }}
                                </option>
                            </select>
                        </div>

                    </div>

                    @error('user')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="table-responsive-md maxScroll mt-2">
                        <table class="table table-bordered table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th class="px-0 text-center" style="width: 42px">
                                        <input type="checkbox" onclick="toggle(this);" />
                                    </th>
                                    <th>{{ __('Thumbnail') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email Address') }}</th>
                                    <th>{{ __('Phone Number') }}</th>
                                </tr>
                            </thead>
                            <tbody id="notificationUsers">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="py-2 px-0 text-center">
                                            <input type="checkbox" name="user[]" value="{{ $user->id }}">
                                        </td>
                                        <td>
                                            <img src="{{ $user->thumbnail }}" alt="" width="40" height="40"
                                                loading="lazy" class="rounded" />
                                        </td>
                                        <td class="py-2">{{ $user->name }}</td>
                                        <td>{{ $user->email ?? '-' }}</td>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        function toggle(source) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }
        };

        $(document).ready(function() {
            $("#deviceType").change(function() {
                var deviceType = $('#deviceType').val();
                if (deviceType) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('admin.customerNotification.filter') }}",
                        dataType: 'json',
                        data: {
                            device_type: deviceType
                        },
                        success: function(response) {
                            $('#notificationUsers').empty()
                            $.each(response.data.users, function(key, value) {
                                console.log(value);
                                $('#notificationUsers').append(
                                    "<tr style='display: table-row;'>\
                                            <td> <input type='checkbox' name='user[]' value='" + value.id + "'></td>\
                                             <td><img src='" + value.profile_photo + "' width='40' height='40' loading='lazy' class='rounded'/></td>\
                                            <td>" + value.name + "</td>\
                                            <td>" + (value.email ?? '-') + "</td>\
                                            <td>" + (value.phone ?? '-') + "</td>\
                                        </tr>"
                                );
                            });
                            if (!response.data.users.length) {
                                $('#notificationUsers').append(
                                    "<tr>\
                                            <td colspan='4'> User list is empty</td>\
                                        </tr>"
                                );
                            }
                        },
                        error: function(e) {
                            $('#notificationUsers').empty()
                            $('#notificationUsers').append(
                                "<tr>\
                                            <td colspan='4'>" + e.responseText + "</td>\
                                        </tr>"
                            );
                        }
                    });
                }
            });
        });
    </script>
@endpush
