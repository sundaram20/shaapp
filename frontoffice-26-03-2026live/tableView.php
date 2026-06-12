<style>
    .mainRoomLiCont {
        display: grid;
        grid-template-columns: repeat(auto-fit, 16rem);
        grid-gap: 6px;
    }

    /* .mainRoomLiCont li{
        flex : 0 0 13.5%;
        margin : 4px!important;
        max-width : 13.5%;
    } */

    @media only screen and (max-width:1210px) {
        .mainRoomLiCont {
            display: grid;
            grid-template-columns: repeat(5, 16rem);
            grid-gap: 6px;
        }



    }

    @media only screen and (max-width:1090px) {
        .mainRoomLiCont {
            display: grid;
            grid-template-columns: repeat(4, 16rem);
            grid-gap: 6px;
        }

    }

    @media only screen and (max-width:940px) {
        .mainRoomLiCont {
            display: grid;
            grid-template-columns: repeat(3, 16rem);
            grid-gap: 6px;
        }

    }

    @media only screen and (max-width:730px) {
        .mainRoomLiCont {
            display: grid;
            grid-template-columns: repeat(2, 16rem);
            grid-gap: 6px;
        }

    }


    .mainRoomLiCont li {

        height: 10rem !important;
    }




    .roomTypeP {
        color: #898787;
        font-size: 1.2rem;
    }




    .guestNameP {
        color: #777575;
        font-size: 1.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 24ch;
    }

    ul.RoomViewsideBtnCont {
        list-style-type: none;
        padding: 0;


    }

    .RoomViewsideBtnCont li {
        margin-bottom: 0.05em;
        /* Adjust spacing between buttons */
    }

    .RoomViewsideBtnCont li a {
        display: block;
        padding: 6px 4px;
        text-decoration: none;
        color: #333;
        /* Text color */
        background-color: #f0f0f0;
        /* Background color */
        border: 1px solid #ccc;
        /* Border color */
        border-radius: 3px;
        transition: background-color 0.3s ease, color 0.3s ease;
        /* Smooth transition */
    }

    .RoomViewsideBtnCont li a:hover {
        background-color: #ddd;
        /* Hover background color */
        color: #555;
        /* Hover text color */
    }

    .RoomViewsideBtnCont li a:active {
        background-color: #007bff;
        /* Active background color */
        color: #fff;
        /* Active text color */
    }



    .RoomViewsideBtnCont .toggle-btn a {
        background: linear-gradient(to bottom, #004289 0%, #007bff 100%);
        color: #fff !important;
    }


    .grid {
        display: grid;
        grid-gap: 10px;
        grid-template-columns: repeat(auto-fit, 186px);
    }

    .grid>* {
        background-color: green;
        height: 200px;
    }
</style>
<div>

    <!--  
<div class="grid">
  <div>1</div>
  <div>2</div>
  <div>3</div>
  <div>4</div>
</div> -->

    <div class="d-flex">

        <div class="col-lg-2" style="padding: 5px!important;">
            <div>
                <ul class="RoomViewsideBtnCont" style="list-style-type: none!important; padding: 0!important;">
                    <li class="toggle-btn" onclick="setRoomTypeUrl('')">
                        <a href="#">
                            <span >All</span>
                        </a>
                    </li>
                    <?php


                    $selectnew = "SELECT * FROM mst_room_types";
                    $resnew = mysqli_query($connNew, $selectnew);
                    while ($rownew = mysqli_fetch_object($resnew)) { ?>

                        <li class="toggle-btn" onclick="setRoomTypeUrl('<?= $rownew->id; ?>')">

                            <a href="#" >
                                <span>
                                    <?= $rownew->name; ?>
                                </span>
                            </a>
                        </li>
                    <?php }
                    ?>
                    <!-- <li class="toggle-btn">

                        <a href="#">
                            <span>Superior Room With Balcony(15)</span>

                        </a>

                    </li>                  
                    <li class="toggle-btn">

                        <a href="#">
                            <span>Superior Room With Balcony(15)</span>

                        </a>

                    </li> -->
                    <!-- Add other buttons here -->
                </ul>
            </div>
        </div>


        <div class="col-lg-10" style="border-left : 1px solid #f4f4f4;">
<div id="ViewSelectedRoom"></div>
            


        </div>


    </div>




</div>