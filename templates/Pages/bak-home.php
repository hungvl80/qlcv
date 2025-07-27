<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Quản lý Dữ liệu - Dạng Lưới (Cập nhật)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background for the whole page */
        }

        /* Styles for the new banner */
        .hero-banner {
            background: linear-gradient(to right, #007bff, #00c0ff); /* Blue gradient */
            color: #fff;
            padding: 40px 0; /* Adjust padding as needed */
            text-align: center;
            border-bottom-left-radius: 15px; /* Rounded corners for banner */
            border-bottom-right-radius: 15px;
            margin-bottom: 50px; /* Space below banner */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .hero-banner .banner-icon {
            font-size: 3.5rem; /* Large icon size */
            margin-bottom: 15px;
            display: block; /* Ensures it's on its own line */
        }
        .hero-banner h1 {
            font-size: 2.5rem; /* Large heading for main title */
            font-weight: 700; /* Bold */
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px; /* Space between icon and text */
        }
        .hero-banner p {
            font-size: 1.1rem; /* Subtitle font size */
            opacity: 0.9;
        }

        /* Existing CSS for category cards (unchanged/minor adjustments for context) */
        .category-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            text-align: center;
            height: 230px;
            position: relative;
        }

        .category-card-icon {
            font-size: 3.5rem;
            margin-bottom: 10px;
            color: #0d6efd;
        }
        .category-title {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1.1rem;
            line-height: 1.3;
            white-space: normal;
        }
        .category-count {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 15px;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-ghost {
            background-color: transparent;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            opacity: 1;
            transition: all 0.3s ease;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 150px;
        }
        .btn-ghost:hover {
            background-color: #0d6efd;
            color: #fff;
        }
        .btn-ghost:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Modal specific styling (unchanged) */
        .modal-body .category-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        .modal-body .category-list li {
            margin-bottom: 5px;
            line-height: 1.4;
        }
        .modal-body .category-list ul {
            list-style: none;
            padding-left: 1.25rem;
            margin-top: 5px;
            margin-bottom: 0;
        }
        .modal-body .category-list ul li {
            font-size: 0.95rem;
            color: #495057;
        }
        .modal-body .category-list ul ul {
            padding-left: 1rem;
        }
        .modal-body .category-list ul ul li {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .modal-body .category-item-title {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #343a40;
        }
    </style>
</head>
<body>

    <div class="hero-banner">
        <div class="container">
            <h1>
                <i class="bi bi-bar-chart-fill"></i> HỆ THỐNG QUẢN LÝ SỐ LIỆU
            </h1>
            <p>Quản lý và truy cập các số liệu quan trọng một cách hiệu quả</p>
        </div>
    </div>

    <div class="container my-5">
        <h2 class="mb-4 text-center">Menu Quản lý Dữ liệu</h2>
        <p class="mb-5 text-center text-muted">Khám phá các danh mục dữ liệu chính. Nhấn "Xem chi tiết" để xem các mục con.</p>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-person-workspace category-card-icon"></i>
                        <div class="category-title">Tư pháp</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalTuPhap">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-currency-dollar category-card-icon"></i>
                        <div class="category-title">Tài chính – Kế hoạch</div>
                        <div class="category-count">Có 3 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalTaiChinh">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-building-fill-gear category-card-icon"></i>
                        <div class="category-title">Xây dựng và Công Thương</div>
                        <div class="category-count">Có 3 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalXayDung">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-tree-fill category-card-icon"></i>
                        <div class="category-title">Nông nghiệp và Môi trường</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalNongNghiep">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-building category-card-icon"></i>
                        <div class="category-title">Nội vụ</div>
                        <div class="category-count">Có 15 danh mục</div> </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalNoiVu">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-book-half category-card-icon"></i>
                        <div class="category-title">Giáo dục và Đào tạo</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalGiaoDuc">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-palette-fill category-card-icon"></i>
                        <div class="category-title">Văn hóa, Khoa học và Thông tin</div>
                        <div class="category-count">Có 3 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalVanHoa">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-heart-pulse-fill category-card-icon"></i>
                        <div class="category-title">Y tế</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalYTe">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-file-earmark-text-fill category-card-icon"></i>
                        <div class="category-title">Thủ tục hành chính</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalThuTuc">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-exclamation-triangle-fill category-card-icon"></i>
                        <div class="category-title">Khiếu nại, tố cáo</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalKhieuNai">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-chat-right-text-fill category-card-icon"></i>
                        <div class="category-title">Phản ánh, kiến nghị</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalPhanAnh">
                        Xem chi tiết
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="category-card">
                    <div>
                        <i class="bi bi-people-fill category-card-icon"></i>
                        <div class="category-title">Tiếp công dân</div>
                        <div class="category-count">Có 2 danh mục</div>
                    </div>
                    <button type="button" class="btn btn-ghost" data-bs-toggle="modal" data-bs-target="#modalTiepCongDan">
                        Xem chi tiết
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTuPhap" tabindex="-1" aria-labelledby="modalTuPhapLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTuPhapLabel"><i class="bi bi-person-workspace me-2"></i> Tư pháp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Các vấn đề pháp lý</li>
                        <li>Văn bản quy phạm pháp luật</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTaiChinh" tabindex="-1" aria-labelledby="modalTaiChinhLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTaiChinhLabel"><i class="bi bi-currency-dollar me-2"></i> Tài chính – Kế hoạch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Ngân sách</li>
                        <li>Đầu tư công</li>
                        <li>Quy hoạch</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalXayDung" tabindex="-1" aria-labelledby="modalXayDungLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalXayDungLabel"><i class="bi bi-building-fill-gear me-2"></i> Xây dựng và Công Thương</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Quy hoạch xây dựng</li>
                        <li>Sản xuất công nghiệp</li>
                        <li>Thương mại dịch vụ</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNongNghiep" tabindex="-1" aria-labelledby="modalNongNghiepLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNongNghiepLabel"><i class="bi bi-tree-fill me-2"></i> Nông nghiệp và Môi trường</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Sản xuất nông nghiệp</li>
                        <li>Bảo vệ môi trường</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNoiVu" tabindex="-1" aria-labelledby="modalNoiVuLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNoiVuLabel"><i class="bi bi-building me-2"></i> Nội vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>
                            <span class="category-item-title">Tổ chức hành chính, sự nghiệp nhà nước</span>
                            <ul>
                                <li>Số lượng Khu phố</li>
                            </ul>
                        </li>
                        <li>Chính quyền địa phương, địa giới đơn vị hành chính</li>
                        <li>
                            <span class="category-item-title">Cán bộ, công chức, viên chức và công vụ</span>
                            <ul>
                                <li>Số đại biểu hội đồng nhân dân</li>
                                <li>Thu nhập bình quân cán bộ, công chức</li>
                                <li>Số lượng cán bộ, công chức, viên chức được đánh giá, xếp loại chất lượng</li>
                                <li>Số lượng cán bộ, công chức, viên chức bị kỷ luật</li>
                                <li>Số lượng cán bộ, công chức, viên chức bị phê bình, nhắc nhở</li>
                                <li>Số lượt cán bộ, công chức, viên chức được đào tạo, bồi dưỡng</li>
                                <li>Số lượng lãnh đạo</li>
                                <li>Tiền lương cán bộ, công chức</li>
                                <li>Tiền lương của viên chức</li>
                                <li>Số lượng vị trí việc làm cán bộ, công chức</li>
                                <li>Kết quả thực hiện chính sách tinh giản biên chế</li>
                                <li>Kết quả sắp xếp các đơn vị hành chính cấp huyện, cấp xã</li>
                                <li>Số lượng cán bộ, công chức cấp huyện, cấp xã dôi dư sau sắp xếp đơn vị hành chính và kết quả giải quyết</li>
                            </ul>
                        </li>
                        <li>Cải cách hành chính</li>
                        <li>
                            <span class="category-item-title">Hội, tổ chức phi chính phủ</span>
                            <ul>
                                <li>Số lượng các quỹ xã hội, quỹ từ thiện</li>
                            </ul>
                        </li>
                        <li>
                            <span class="category-item-title">Thi đua, khen thưởng</span>
                            <ul>
                                <li>Số phong trào thi đua</li>
                                <li>Số lượng khen thưởng các cấp</li>
                            </ul>
                        </li>
                        <li>
                            <span class="category-item-title">Văn thư, lưu trữ nhà nước</span>
                            <ul>
                                <li>Số lượng hồ sơ</li>
                                <li>Số tài liệu lưu trữ</li>
                                <li>Số tài liệu lưu trữ thu thập</li>
                                <li>Số tài liệu lưu trữ đưa ra sử dụng</li>
                                <li>Diện tích kho lưu trữ</li>
                                <li>Số trang thiết bị dùng cho lưu trữ</li>
                                <li>Kinh phí cho hoạt động lưu trữ</li>
                            </ul>
                        </li>
                        <li>Thanh niên</li>
                        <li>Lao động, tiền lương</li>
                        <li>Việc làm</li>
                        <li>Bảo hiểm xã hội</li>
                        <li>An toàn, vệ sinh lao động</li>
                        <li>Người có công</li>
                        <li>Bình đẳng giới</li>
                        <li>
                            <span class="category-item-title">Công tác dân tộc và tín ngưỡng, tôn giáo</span>
                            <ul>
                                <li>Dân tộc</li>
                                <li>Số tín ngưỡng</li>
                                <li>Số tổ chức tôn giáo</li>
                                <li>Số chức sắc, chức việc, tín đồ</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGiaoDuc" tabindex="-1" aria-labelledby="modalGiaoDucLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGiaoDucLabel"><i class="bi bi-book-half me-2"></i> Giáo dục và Đào tạo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Giáo dục phổ thông</li>
                        <li>Giáo dục đại học</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVanHoa" tabindex="-1" aria-labelledby="modalVanHoaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVanHoaLabel"><i class="bi bi-palette-fill me-2"></i> Văn hóa, Khoa học và Thông tin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Hoạt động văn hóa</li>
                        <li>Nghiên cứu khoa học</li>
                        <li>Công nghệ thông tin</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalYTe" tabindex="-1" aria-labelledby="modalYTeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalYTeLabel"><i class="bi bi-heart-pulse-fill me-2"></i> Y tế</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Chăm sóc sức khỏe</li>
                        <li>Y tế dự phòng</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalThuTuc" tabindex="-1" aria-labelledby="modalThuTucLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalThuTucLabel"><i class="bi bi-file-earmark-text-fill me-2"></i> Thủ tục hành chính</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Dịch vụ công</li>
                        <li>Hồ sơ hành chính</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKhieuNai" tabindex="-1" aria-labelledby="modalKhieuNaiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKhieuNaiLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i> Khiếu nại, tố cáo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Tiếp nhận khiếu nại</li>
                        <li>Xử lý tố cáo</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPhanAnh" tabindex="-1" aria-labelledby="modalPhanAnhLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPhanAnhLabel"><i class="bi bi-chat-right-text-fill me-2"></i> Phản ánh, kiến nghị</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Tiếp nhận phản ánh</li>
                        <li>Xử lý kiến nghị</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTiepCongDan" tabindex="-1" aria-labelledby="modalTiepCongDanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTiepCongDanLabel"><i class="bi bi-people-fill me-2"></i> Tiếp công dân</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="category-list">
                        <li>Lịch tiếp dân</li>
                        <li>Kết quả tiếp dân</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>