<?php
/**
 * Created by PhpStorm.
 * User: neov
 * Date: 30.10.14
 * Time: 20:39
 */

/* Наша функция-хэндлер */
function user_log ($errno, $errmsg, $file, $line) {
    // время события
    $timestamp = time();

    //формируем новую строку в логе
    $err_str = $timestamp.'||';
    $err_str .= $errno.'||';
    $err_str .= $file.'||';
    $err_str .= $line.'||';
    $err_str .= $errmsg."\n";

    //проверка на максимальный размер
    if (is_file(LOG_FILE_NAME) AND filesize(LOG_FILE_NAME)>=(LOG_FILE_MAXSIZE*1024)) {
        //проверяем настройки, если установлен лог_ротэйт,
        //то "сдвигаем" старые файлы на один вниз и создаем пустой лог
        //если нет - чистим и пишем вместо старого лога
        if (LOG_ROTATE===true) {
            $i=1;
            //считаем старые логи в каталоге
            while (is_file(LOG_FILE_NAME.'.'.$i)) { $i++; }
            $i--;
            //у каждого из них по очереди увеличиваем номер на 1
            while ($i>0) {
                rename(LOG_FILE_NAME.'..'.$i,LOG_FILE_NAME. '.' .(1+$i--));
            }
            rename (LOG_FILE_NAME,LOG_FILE_NAME.'.1');
            touch(LOG_FILE_NAME);
        }
        elseif(is_file(LOG_FILE_NAME)) {
            //если пишем логи сверху, то удалим
            //и создадим заново пустой файл
            unlink(LOG_FILE_NAME);
            touch(LOG_FILE_NAME);
        }
    }

    /*
    проверяем есть ли такой файл
    если нет - можем ли мы его создать
    если есть - можем ли мы писать в него
    */
    if(!is_file(LOG_FILE_NAME)) {
        if (!touch(LOG_FILE_NAME)) {
            return 'can\'t create log file';
        }
    }
    elseif(!is_writable(LOG_FILE_NAME)) {
        return 'can\'t write to log file';
    }

    //обратите внимание на функцию, которой мы пишем лог.
    error_log($err_str, 3, LOG_FILE_NAME);
}

?>