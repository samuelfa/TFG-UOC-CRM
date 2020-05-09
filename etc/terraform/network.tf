resource "google_compute_network" "vpc_network" {
  name = var.network
  auto_create_subnetworks = "true"
}