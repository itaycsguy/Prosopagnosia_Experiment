import os
# install all package that we need for the module to run:
class PackagesInstaller():
	@staticmethod
	def install():
		os.system("py -m pip install -U pip")
		os.system("py -m pip install xlsxwriter")
		os.system("py -m pip install glob")
		os.system("py -m pip install datetime")
		print("installed successfully.")

if __name__ == "__main__":
	PackagesInstaller.install()