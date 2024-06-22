<div class="user_game_dashboard">
    <div style="display: none;" class="game_verify game_verify_phone center_div">
        <a onclick="popup_load('game_verify')">
            <img src="/assets/img/level/menu/gift.png">
            <p class="name text_game_verify"></p>
            <div class="list_reward list_item" style="margin: 30px auto;">
            </div>
        </a>
        <div class="button_style">
            <button onclick="popup_load('game_verify')"  style="width: auto;margin: 10px auto;" class="button_yes text_button_verify"></button>
        </div>
    </div>
    <div style="display: none;" class="game_character_create game_verify center_div">
        <img src="/assets/img/level/menu/character_create.png">
        <p class="name text_join_game"></p>
        <div class="list_reward list_item" style="margin: 30px auto;">
        </div>
        <div class="button_style">
            <button onclick="character_create(this)"  style="width: auto;margin: 10px auto;" class="button_yes text_button_join"></button>
            <button onclick="window.open('https://giangnt101993.gitbook.io/tu-tien-gioi/')"  style="width: auto;margin: 10px auto;" class="button_no text_game_guide"></button>
        </div>
    </div>
    <div class="div_module">
        <div style="display: none;" class="function">
            <div class="current_balance currency_div">
                <div onclick="popup_load('game_bonus_exp')" class="item">
                    <img class="icon" src="/assets/img/level/icon/mini_exp.png">
                    <p class="exp_bonus">0</p>
                    <i class="fas fa-question"></i>
                </div>
            </div>
            <h5 class="text_function"></h5>
            <div class="list list_fr">
                <div class="item" onclick="window.open('https://giangnt101993.gitbook.io/tu-tien-gioi/')">
                    <img src="/assets/img/level/menu/guide.png">
                    <p style="color: var(--yellow-color);" class="text_button_guide"></p>
                </div>
            </div>
        </div>
        <div style="display: none;margin-top: 50px;" class="acitivy">
            <h5 class="text_acitivity"></h5>
            <div class="list list_fr">
            </div>
        </div>
    </div>
</div>
<script> 
    page = 1;
    async function content_render() {
        var my_info = await get_data_by_url('/api/user_data?user='+token_user+'&data=info');
        if(typeof(my_character_data) == 'undefined' && my_character != 0){
            my_character_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=info&id='+my_character);
            my_character_data.info = JSON.parse(my_character_data.info);
        }
        if(my_character == 0){
            time_verify = 0;
        }else{
            get_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=setting&id='+my_character);
            character_setting = JSON.parse(get_data.setting);
            time_verify = getSafe(() => character_setting.phone.time, 1);
        }
        if(time_verify < server_time){
            if(time_verify == 0){
                $(".user_game_dashboard .game_character_create").show();
            }else{
                var gift_list = {"gold":{"type":"currency","amount":5000},"energy":{"type":"item","amount":20}}
                html = "";
                $.each(gift_list, function(key, value) {
                    html += item_render(key,value.type,value.amount);
                })
                $(".user_game_dashboard .game_verify_phone .list_reward").html(html);
                $(".user_game_dashboard .game_verify_phone").show();
            }
        }else{
            var top_level = parseInt(server_data.setting.level);
            var distance_level = top_level - my_character_data.info.level.num;
            distance_level = distance_level < 0 ? 0 :distance_level;
            exp_bonus = distance_level*5;
            $(".current_balance .exp_bonus").html("+"+exp_bonus+"%");
            
            var function_list = ["character","skill","bag","job","shop","market","party","bank","referral","top","guild","equipment_upgrade","gift","pet"];
            html = "";
            $.each(function_list, function(key, value) {
                html += `<div class="item" onclick="load_module('content','user_game_${value}')">
                            <img src="/assets/img/level/menu/${value}.png">
                            <p>${json_data.language['menu_function_'+value]}</p>
                        </div>`;
            })
            $(".user_game_dashboard .function .list").prepend(html);
            $(".user_game_dashboard .function").show();
            var activity_list = ["dungeon","dragon_tomb","training","legendary","angel_devil","area","word","ancient","guild_transpot","battle_doa","battle_champion","pet_champion","black_prison"];
            html = "";
            $.each(activity_list, function(key, value) {
                html += `<div class="item" onclick="load_module('content','user_game_${value}')">
                            <img src="/assets/img/level/menu/${value}.png">
                            <p>${json_data.language['menu_activity_'+value]}</p>
                        </div>`;
            })
            $(".user_game_dashboard .acitivy .list").html(html);
            $(".user_game_dashboard .acitivy").show();
        }
    }
    function character_create(e){
        $(e).prop('disabled', true);
        $.post("/assets/ajax/user.php", {action: "character_create"}, function(data) {
            $("#result").html(data);
            $(e).show();
        });
    }
    setTimeout(() => {
        content_render();
    }, 200);
    language_render(['text_game_verify','text_button_verify','text_join_game','text_button_join','text_game_guide','text_function','text_acitivity','text_button_guide']);
</script>
