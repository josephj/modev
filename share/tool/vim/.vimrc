 function! Phpcs()
  ! /var/www/josephj/lab/modev/bin/phpcs "%"
  cwindow
endfunction
autocmd BufWritePost *.php call Phpcs()
command! Phpcs execute Phpcs()

function! PhpChecker()
  ! /home/y/bin/php -l "%"
  cwindow
endfunction
autocmd BufWritePost *.php call PhpChecker()
command! PhpChecker execute PhpChecker()

