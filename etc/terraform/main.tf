provider "google" {
  credentials = file("TFG-CRM-f30414610b7d.json")
  project = var.project-name
  region  = var.region
  zone    = var.zone
}

resource "google_compute_instance" "vm_instance" {
  name         = "frontal"
  machine_type = "f1-micro"

  tags = [
    "${var.network}-firewall-ssh",
    "${var.network}-firewall-http",
    "${var.network}-firewall-https",
    "${var.network}-firewall-icmp",
    "${var.network}-firewall-mysql",
    "${var.network}-firewall-secure-forward",
  ]

  boot_disk {
    initialize_params {
      image = "debian-cloud/debian-10"
    }
  }

  network_interface {
    network = google_compute_network.vpc_network.self_link
    access_config {
    }
  }
}