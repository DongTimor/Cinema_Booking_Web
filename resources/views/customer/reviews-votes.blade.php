<div class="modal fade" id="vote-modal" tabindex="-1" role="dialog" aria-labelledby="vote-modal-label"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content vote-modal-content">
        <div class="modal-header vote-modal-header">
            <img class="modal-img"
                src="{{ $movie->images->skip(1)->first() ? asset($movie->images->skip(1)->first()->url) : asset('default-image.jpg') }}"
                alt="Movie Image" class="w-full h-48 object-cover">
            <button type="button" class="close" onclick="closeVoteModal()">&times;</button>
        </div>
        <div class="modal-body text-center vote-modal-body">
            <h5 class="modal-title font-bold vote-modal-title">Red One: Mật Mã Đỏ</h5>
            <div class="circle-progress">
                <div class="circle">
                    <div class="mask full">
                        <div class="fill"></div>
                    </div>
                    <div class="mask half">
                        <div class="fill"></div>
                        <div class="fill fix"></div>
                    </div>
                    <div class="inside-circle">
                        <span class="rating"><i class="fa fa-star" style="color: #ffb71c"></i>5.4</span>
                        <p style="font-size: 12px">(24 đánh giá)</p>
                    </div>
                </div>
            </div>
            <div class="star-rating">
                <span class="star" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
                <span class="star" data-value="6">&#9733;</span>
                <span class="star" data-value="7">&#9733;</span>
                <span class="star" data-value="8">&#9733;</span>
                <span class="star" data-value="9">&#9733;</span>
                <span class="star" data-value="10">&#9733;</span>
            </div>
        </div>
        <div class="modal-footer vote-modal-footer">
            <button type="button" class="btn" style="background-color: #e2e2e2"
                onclick="closeVoteModal()">Đóng</button>
            <button type="button" class="btn"
                style="background-color: rgba(233, 142, 22, 0.685); color: white" onclick="applyVote()">
                <i class="fa fa-comment"></i> Xác Nhận
            </button>
        </div>
    </div>
</div>
</div>
