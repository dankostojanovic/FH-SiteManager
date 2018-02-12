
Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"

  config.vm.network "forwarded_port", guest: 80, host: 8080,host_ip: "127.0.0.1"
  config.vm.network "forwarded_port", guest: 3306, host: 3307,host_ip: "127.0.0.1", auto_correct: true

  config.vm.provider "virtualbox" do |vb|
    vb.memory = "2048"
    vb.customize [ "guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 1000 ]
  end

  config.vm.provision "shell", path: "install/provision/vagrant-install.sh"
#  config.vm.provision "shell", path: "vagrant/config.sh"
#  config.vm.provision "shell", path: "vagrant/startup.sh", run: "always"
end
