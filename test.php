<?php
$hash = '$2y$10$lx3to/t4DIQwC.Gfm3MTr.NjKxkbChg.BhsiX25A7qUbI50MJrT5K';

if (password_verify('123', $hash)) {
    echo "Mật khẩu ĐÚNG!";
} else {
    echo "Mật khẩu SAI!";
}
