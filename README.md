# ip_scan
Quick PHP Script to scan the connected devices to a network

With this simple script you can scan all the devices connected to your network that respond to the port 80, just in case you lost or do not remember the IP adrress of a certain device.

USAGE:

    $ip_area = "192.168.1.";

At the beginning of the file, change the $ip_area variable with your network informations.


    $file_to_scan = "index.html";

You can leave this empty if you don't wanna scan for a specific file, otherwise fill this in with the path of your choice.
