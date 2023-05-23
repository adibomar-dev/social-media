@extends('layout.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3 py-3">
                <img src="{{ asset('images/social-text.png') }}" class="logo">

                <div class="categories-section pt-2">
                    <div class="feed-boxes">

                    </div>
                </div>
            </div>
            <div class="col-md-6 feed-content-section">
                <div class="feed-header fw-bold py-3">
                    Feeds
                </div>
                <div class="append-feed overflow-hidden">

                    <!-- Shimmer -->
                    @for ($i = 0; $i < 6; $i++)
                        <div class="feed-boxes shimmers">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="shimmer-wrapper d-flex align-items-start">
                                        <div class="shimmer-circle shimmer-circle-md shimmer-animate d-inline-block float-left"></div>
                                        <div class="flex-grow-1">
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-0 ms-2" style="height: 20px; width: 100%"></div>
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-0 ms-2" style="height: 40px; width: 100%"></div>
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-0 ms-2" style="height: 20px; width: 70px"></div>
                                            <br>
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-2 ms-2" style="height: 20px; width: 30px"></div>
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-2 ms-2" style="height: 20px; width: 30px"></div>
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-2 ms-2" style="height: 20px; width: 30px"></div>
                                            <div class="shimmer-line shimmer-line-br shimmer-animate d-inline-block float-left mt-2 ms-2" style="height: 20px; width: 30px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor

                </div>
            </div>

            <div class="col-md-3 py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="material-symbols-outlined">
                            notifications
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="username me-3">
                            Adib Omar
                        </div>
                        <div class="user-profile">
                            <img src="{{ asset('images/self-pic.jpeg') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajax({
                url: "{{ route('getFeed') }}",
                type: 'GET',
                dataType: 'json', // added data type
                success: function(res) {
                    var feedBoxes = `
                        <div class="feed-boxes">
                            <div class="row">
                                <div class="col-md-11 d-flex">
                                    <div class="feed-user-img">
                                        <img src="__userImg__">
                                    </div>
                                    <div class="feed-content">
                                        <div class="name">
                                            __userName__
                                        </div>
                                        <div class="feed">
                                            __userText__
                                        </div>
                                        <div class="feed-pill-section py-2">
                                            <div class="badge badge-pill bg-__pillColour__">
                                                __feedCategory__
                                            </div>
                                        </div>
                                        __feedImage__
                                        <div class="feed-action pt-2">
                                            <span class="material-symbols-outlined">
                                                favorite
                                            </span>
                                            <span class="material-symbols-outlined ms-3">
                                                mode_comment
                                            </span>
                                            <span class="material-symbols-outlined ms-3">
                                                share
                                            </span>
                                            <span class="material-symbols-outlined ms-3">
                                                cached
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 feed-votes text-center">
                                    <span class="material-symbols-outlined">
                                        arrow_upward
                                    </span>
                                    <span class="no-votes">
                                        __numberOfVotes__
                                    </span>
                                    <span class="material-symbols-outlined">
                                        arrow_downward
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;

                    res.forEach(function (item) {
                        // Feed image.
                        var feedImage = `
                            <div class="feed-image">
                                __feedImgUrl__
                            </div>
                        `;

                        // Check if the image is included.
                        if (item.post.category == 'shopping' || item.post.category == 'memes') {
                            feedImage = feedImage.replace('__feedImgUrl__', '<img class="w-100 mt-2" src="' + item.post.feedContent.post.image + '">');
                        } else {
                            feedImage = '';
                        }

                        // Format the feed to place in the HTML.
                        var formattedFeedBox = feedBoxes.replace('__userName__', item.user.name)
                            .replace('__userImg__', item.user.image)
                            .replace('__feedCategory__', item.post.category)
                            .replace('__numberOfVotes__', item.post.numberOfVotes)
                            .replace('__userText__', item.post.feedContent.post.text)
                            .replace('__pillColour__', item.post.color)
                            .replace('__feedImage__', feedImage);

                        // Append formatted feed.
                        $('.append-feed').append(formattedFeedBox);

                        // Remove shimmer.
                        $('.shimmers').remove();
                    });
                }
            });
        });
    </script>
@endsection
