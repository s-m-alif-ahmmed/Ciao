@extends('backend.app')

@section('title', 'Dashboard')

@section('content')
{{--     PAGE-HEADER--}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>
    </div>
{{--     PAGE-HEADER--}}


    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $total_users }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Users</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                       <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $total_valets }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Valets</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 516.037 516.037" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                        <g>
                                            <path d="M408.644 411.413c-4.39-3.356-10.664-2.514-14.018 1.873-3.354 4.388-2.515 10.664 1.873 14.018l17.089 13.061a9.965 9.965 0 0 0 6.068 2.055 9.985 9.985 0 0 0 7.416-3.287l32.664-36.066c3.707-4.094 3.395-10.418-.699-14.125-4.093-3.707-10.418-3.394-14.125.699l-26.488 29.248z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                            <path d="M514.82 358.033a9.999 9.999 0 0 0-9.126-9.737c-23.789-2.079-53.063-18.059-74.582-40.708a10.004 10.004 0 0 0-7.247-3.112h-.003a10.002 10.002 0 0 0-7.245 3.107c-9.659 10.153-20.871 18.96-32.441 25.781-20.302-22.664-45.29-39.362-74.577-49.873a9.951 9.951 0 0 0-.777-.277 204.88 204.88 0 0 0-6.922-2.313c24.768-24.702 41.428-57.164 41.428-86.285v-15.711c11.708-5.098 19.919-16.784 19.919-30.357 0-14.099-8.874-26.158-21.327-30.903-5.14-49.901-40.297-91.849-88.59-105.727C251.903 5.121 245.864 0 238.65 0h-36.237c-6.958 0-12.807 4.768-14.495 11.205-23.755 6.335-45.453 19.857-61.721 38.657-16.512 19.082-26.78 42.912-29.388 67.788-12.459 4.748-21.336 16.804-21.336 30.899 0 13.578 8.223 25.267 19.947 30.362v15.706c0 29.094 16.663 61.568 41.43 86.287a205.292 205.292 0 0 0-6.725 2.239c-.436.124-.86.277-1.273.458-32.78 11.825-60.147 31.399-81.579 58.43-20.612 25.998-35.265 58.103-43.551 95.431-2.949 13.34-5.167 30.104 3.929 41.43 8.885 11.063 24.783 12.481 37.642 12.481h65.415c5.523 0 10-4.478 10-10s-4.477-10-10-10H45.293c-11.963 0-19.381-1.684-22.048-5.005-2.825-3.518-2.823-11.791.003-24.581 10.393-46.812 35.805-104.097 97.032-133.02-1.883 33.783 16.422 55.865 28.057 69.88a10 10 0 0 0 14.666.783l56.358-54.808 56.385 54.81a10.002 10.002 0 0 0 14.671-.792c11.619-14.027 29.897-36.122 28.031-69.834 17.763 8.426 33.457 19.695 46.953 33.733-8.059 3.119-15.983 5.112-23.372 5.758a10 10 0 0 0-9.126 9.729c-.837 35.753 5.122 61.368 21.244 91.349.136.254 5.584 10.02 14.073 22H201.241c-5.523 0-10 4.478-10 10s4.477 10 10 10h182.861c12.056 13.324 26.275 24.664 39.759 24.664 32.532 0 69.356-65.997 69.71-66.657 16.096-29.986 22.053-55.597 21.249-91.349zM321.508 115.486H266.45c-10.402-27.173-12.37-49.58-12.723-82.473 36.069 12.757 62.29 44.583 67.781 82.473zM207.408 20h26.246c.063 38.708 1.044 64.924 11.523 95.486h-49.272C206.366 84.923 207.346 58.704 207.408 20zm-20.063 12.224c-.33 33.326-2.242 55.871-12.708 83.263h-57.402c5.704-38.383 33.296-71.072 70.11-83.263zm-78.781 103.262h66.898c.589.478 1.159.539 1.637 0h153.085c7.203 0 13.063 5.859 13.063 13.063 0 7.219-5.859 13.092-13.063 13.092h-221.62c-7.218 0-13.091-5.873-13.091-13.092 0-7.203 5.872-13.063 13.091-13.063zm6.856 59.131V181.64h207.907v12.977c0 38.126-36.936 85.279-79.044 100.91-18.425 6.847-31.506 6.841-49.974-.022-42.025-15.695-78.889-62.84-78.889-100.888zm41.502 162.828c-10.35-13.512-20.316-31.252-15.526-57.003a192.758 192.758 0 0 1 13.695-3.872c10.084 7.398 20.96 13.466 32.235 17.677a120.573 120.573 0 0 0 10.537 3.383zm124.894-.006-40.966-39.821a119.713 119.713 0 0 0 10.396-3.342c11.331-4.206 22.26-10.286 32.391-17.708a191.256 191.256 0 0 1 13.687 3.865c4.801 25.715-5.161 43.473-15.508 57.006zm194.13 82.489c-11.826 22.071-39.521 56.109-52.085 56.109s-40.259-34.038-52.092-56.123c-13.215-24.572-18.638-45.063-18.932-72.963 10.788-2.053 22.019-6.079 33.063-11.701a9.978 9.978 0 0 0 1.137-.59c12.896-6.703 25.508-15.574 36.82-26.117 21.298 19.87 47.227 33.883 71.02 38.409-.315 27.904-5.737 48.399-18.931 72.976z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                            <path d="M155.974 471.373c-5.523 0-10 4.478-10 10s4.477 10 10 10h.057c5.523 0 9.971-4.478 9.971-10s-4.505-10-10.028-10z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $total_pending_valets }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Pending Valets</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                        <g>
                                            <path d="M346 285v-80H166v80c0 38.66 31.34 70 70 70h40c38.66 0 70-31.34 70-70zM406 235c0-16.569-13.431-30-30-30v60c16.569 0 30-13.431 30-30zM106 235c0 16.569 13.431 30 30 30v-60c-16.568 0-30 13.431-30 30zM131 175h250c8.284 0 15-6.716 15-15s-6.716-15-15-15H131c-8.284 0-15 6.716-15 15s6.716 15 15 15zM428.989 381.372A175.413 175.413 0 0 0 391 348.224V512h65c9.143.058 15.866-8.067 15-17 0-41.678-14.92-82.032-42.011-113.628zM83.011 381.371C55.92 412.968 41 453.322 41 495c-.867 8.934 5.858 17.059 15 17h65V348.223a175.39 175.39 0 0 0-37.989 33.148zM311.298 35.048l-5.599 39.195C303.587 89.022 290.93 100 276 100h-40c-14.93 0-27.587-10.978-29.698-25.757l-5.599-39.195C172.112 51.697 151.701 80.817 147.03 115h217.941c-4.672-34.183-25.083-63.303-53.673-79.952z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                            <path d="M236 70h40l7.554-52.879C284.845 8.085 277.833 0 268.705 0h-25.41c-9.128 0-16.14 8.085-14.849 17.121zM266.796 385h-21.592L256 408.751z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                            <path d="m301.219 381.768-31.563 69.439a15 15 0 0 1-27.311 0l-31.563-69.439c-25.117-6.555-46.417-22.653-59.782-44.175V512h210V337.593c-13.365 21.522-34.665 37.62-59.781 44.175z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $shops }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Shops</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0L109.6 0C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9c0 0 0 0-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3L448 384l-320 0 0-133.4c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3L64 384l0 64c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-64 0-131.4c-4 1-8 1.8-12.3 2.3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $categories }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Categories</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $subCategories }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Sub Categories</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $products }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Products</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                        <g>
                                            <path d="M10.48 11.15a4.285 4.285 0 0 0 .77.24v9.52a2.64 2.64 0 0 1-.47-.17l-6-2.67A3 3 0 0 1 3 15.33V8.67a2.955 2.955 0 0 1 .11-.79zm4.34-2.23L6.67 5.09l-1.89.84a2.909 2.909 0 0 0-.91.63l7.21 3.21a2.268 2.268 0 0 0 1.84 0zm5.31-2.36a2.909 2.909 0 0 0-.91-.63l-6-2.67a2.966 2.966 0 0 0-2.44 0L8.49 4.28l8.15 3.83zm.76 1.32-3.51 1.56v2.45a.75.75 0 1 1-1.5 0V10.1l-2.36 1.05a5.275 5.275 0 0 1-.77.24v9.52a2.64 2.64 0 0 0 .47-.17l6-2.67A3 3 0 0 0 21 15.33V8.67a2.955 2.955 0 0 0-.11-.79z" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $orders }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Completed Orders</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-md-6 col-xl-3">
            <div class="card-link">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-2 fw-semibold">{{ $pending_orders }}</h3>
                                <p class="text-muted fs-13 mb-0">Total Pending Orders</p>
                            </div>
                            <div class="col col-auto top-icn dash">
                                <div class="counter-icon bg-primary dash ms-auto box-shadow-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')

@endpush


