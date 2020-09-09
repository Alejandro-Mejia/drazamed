
@extends('design.layout.app')


@section('content')

    <template id="chat-panel">
        <div id="chat">
            <div class="container" style="margin-top:200px; margin-bottom:100px;width:400px;" id="chat">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">Chats</div>

                            <div class="panel-body">
                                <chat-messages :messages="messages"></chat-messages>
                            </div>
                            <div class="panel-footer">
                                <chat-form
                                    v-on:messagesent="addMessage"
                                    :user="{{ Auth::user() }}"
                                ></chat-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </template>
@endsection
