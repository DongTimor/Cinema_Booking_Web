<div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-labelledby="comment-modal-label"
    aria-hidden="true">
    <div class="modal-dialog comment-modal-dialog" role="document">
        <div class="modal-content comment-modal-content">
            <div class="modal-header comment-modal-header">
                <div class="header-container">
                    <div class="header-background"
                        style="background-image: url({{ $movie->images->skip(1)->first() ? asset($movie->images->skip(1)->first()->url) : asset('default-image.jpg') }});">
                    </div>
                    <div class="comment-header-content">
                        <img class="comment-modal-img"
                            src="{{ $movie->images->skip(1)->first() ? asset($movie->images->skip(1)->first()->url) : asset('default-image.jpg') }}"
                            alt="Movie Image" class="w-full h-48 object-cover">
                        <div class="comment-header-content-text">
                            <p class="comment-header-movie-title">{{ $movie->name }}</p>
                            <p class="comment-header-lable">User Review</p>
                        </div>
                    </div>
                    <button type="button" class="close" onclick="closeCommentModal()">&times;</button>

                </div>

            </div>
            <div class="modal-body comment-modal-body text-center">
                <div class="comment-modal-body-header">
                    <p>78 Reviews</p>
                    <button class="review-button" onclick="openCommentModalInput()"><i class="fa fa-plus"></i> Review</button>
                </div>
                <div class="comment-modal-body-container">
                    <div class="user-reviews-container">
                        <div class="review-name">
                            <p>John Doe</p>
                            <i class="fa fa-chevron-right icon-right"></i>
                        </div>
                        <div class="review-content">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit. Quisquam,
                                quos.consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit.
                                Quisquam, quos.
                            </p>
                        </div>
                        <div class="review-footer">
                            <div class="review-votes">
                                <i class="fa fa-thumbs-up review-more"></i>
                                <p class="text-sm">Helpful</p>
                                <span class="dot">.</span>
                                <p>123</p>
                                <i class="fa fa-thumbs-down review-more" style="margin-left: 20px;"></i>
                                <p>123</p>
                            </div>
                            <div class="review-more">
                                <i class="fa fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="sub-triangle">
                        </div>
                        <div class="triangle">
                        </div>
                    </div>
                    <div class="comment-modal-body-divider">
                        <p>Write by John Doe</p>
                        <span>.</span>
                        <p>Sep 20, 2024</p>
                    </div>
                </div>
                <div class="comment-modal-body-container">
                    <div class="user-reviews-container">
                        <div class="review-name">
                            <p>John Doe</p>
                            <i class="fa fa-chevron-right icon-right"></i>
                        </div>
                        <div class="review-content">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit. Quisquam,
                                quos.consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit.
                                Quisquam, quos.
                            </p>
                        </div>
                        <div class="review-footer">
                            <div class="review-votes">
                                <i class="fa fa-thumbs-up review-more"></i>
                                <p class="text-sm">Helpful</p>
                                <span class="dot">.</span>
                                <p>123</p>
                                <i class="fa fa-thumbs-down review-more" style="margin-left: 20px;"></i>
                                <p>123</p>
                            </div>
                            <div class="review-more">
                                <i class="fa fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="sub-triangle">
                        </div>
                        <div class="triangle">
                        </div>
                    </div>
                    <div class="comment-modal-body-divider">
                        <p>Write by John Doe</p>
                        <span>.</span>
                        <p>Sep 20, 2024</p>
                    </div>
                </div>                <div class="comment-modal-body-container">
                    <div class="user-reviews-container">
                        <div class="review-name">
                            <p>John Doe</p>
                            <i class="fa fa-chevron-right icon-right"></i>
                        </div>
                        <div class="review-content">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit. Quisquam,
                                quos.consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit.
                                Quisquam, quos.
                            </p>
                        </div>
                        <div class="review-footer">
                            <div class="review-votes">
                                <i class="fa fa-thumbs-up review-more"></i>
                                <p class="text-sm">Helpful</p>
                                <span class="dot">.</span>
                                <p>123</p>
                                <i class="fa fa-thumbs-down review-more" style="margin-left: 20px;"></i>
                                <p>123</p>
                            </div>
                            <div class="review-more">
                                <i class="fa fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="sub-triangle">
                        </div>
                        <div class="triangle">
                        </div>
                    </div>
                    <div class="comment-modal-body-divider">
                        <p>Write by John Doe</p>
                        <span>.</span>
                        <p>Sep 20, 2024</p>
                    </div>
                </div>                <div class="comment-modal-body-container">
                    <div class="user-reviews-container">
                        <div class="review-name">
                            <p>John Doe</p>
                            <i class="fa fa-chevron-right icon-right"></i>
                        </div>
                        <div class="review-content">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. Lorem ipsum
                                dolor sit amet
                                consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur
                                adipisicing
                                elit. Quisquam, quos.Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam,
                                quos.Lorem
                                ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.Lorem ipsum dolor sit
                                amet
                                consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit. Quisquam,
                                quos.consectetur adipisicing elit. Quisquam, quos.consectetur adipisicing elit.
                                Quisquam, quos.
                            </p>
                        </div>
                        <div class="review-footer">
                            <div class="review-votes">
                                <i class="fa fa-thumbs-up review-more"></i>
                                <p class="text-sm">Helpful</p>
                                <span class="dot">.</span>
                                <p>123</p>
                                <i class="fa fa-thumbs-down review-more" style="margin-left: 20px;"></i>
                                <p>123</p>
                            </div>
                            <div class="review-more">
                                <i class="fa fa-ellipsis-v"></i>
                            </div>
                        </div>
                        <div class="sub-triangle">
                        </div>
                        <div class="triangle">
                        </div>
                    </div>
                    <div class="comment-modal-body-divider">
                        <p>Write by John Doe</p>
                        <span>.</span>
                        <p>Sep 20, 2024</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #e2e2e2"
                    onclick="closeCommentModal()">Đóng</button>
                <button type="button" class="btn" style="background-color: rgba(233, 142, 22, 0.685); color: white"
                    onclick="applyComment()">
                    <i class="fa fa-comment"></i> Xác Nhận
                </button>
            </div>
        </div>
    </div>
</div>
