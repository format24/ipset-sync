# IPSet-sync
Цей проєкт автоматизує завантаження IP-префіксів з зовнішніх джерел, формує списки у форматі `ipset` та імпортує їх у систему.

## 📁 Структура проєкту
project/
├── init.php # Запускає всі скрипти з scripts/ і зберігає результат у data/
├── ipset # Створює ipset list та імпортує всі .txt з data/ в ipset
├── ipset-live # Імпортує всі .txt з data/ в ipset
├── scripts/ # Скрипти, які повертають контент списків
│ └── googlebots.php # Приклад: завантажує IP GoogleBot'ів
├── data/ # Згенеровані файли для імпорту ipset
│ └── googlebots.txt
├── logs/ # Логи виконання
├── init.log
└── import.log

Запускає всі get*() функції у scripts/*.php.

Кожна функція має повертати текст у форматі ipset restore.

Результати записуються у data/*.txt.

#Додавання нових джерел:

Створи файл scripts/назва.php.

Всередині реалізуй функцію:

<?php
function getcustomlist(): string {
    return "add googlebots 192.178.6.224/27\n
            add googlebots 192.178.6.32/27\n";
}
## Cron
0 * * * 1 php /etc/ipset-sync/init.php
0 * * 1 /etc/ipset-sync/ipset-live
## Systemd служба
Створіть службу
cat /etc/systemd/system/ipset-restore.service 
[Unit]
Description=Restore ipset rules
Before=netfilter-persistent.service
After=network.target

[Service]
Type=oneshot
ExecStart=/bin/sh -c '/etc/ipset-sync/ipset'
RemainAfterExit=yes

[Install]
WantedBy=multi-user.target
