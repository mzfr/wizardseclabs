import sys
from fractions import gcd

import gmpy2
from Crypto.Util.number import *
from gmpy2 import mpz, next_prime

sys.setrecursionlimit(5000)
e = 65537
n1=71237754398659022763819898227305762645823665465198999306154928837171761926739480275007811436661440807635896080527500733412343387333769310868344284758411788720225834405692768099842037584770484721490061333753695534098780504262862559833641894092088573028934603695262856923795865793432318189787622803197444801553

c1=45145515011623642780376178247739142779375983355174927923166368502843586994822791400776041426783662154412453516501201210514466069751965254119557492923230467881402762422414634669162308744977936996053563273498343210680083606906352850318195547849637040247467709861263179000528671526826699585239590433585962825140

n2=112498216806231476981072811370855496645480555988067666914968926117694402721048243348294537914471890231490104429622252791411495791760514233978215101847101048012480246430405797512729239809215673800330492879627234159135486188344159995775120256420311677489757410992019445794678424392142581556220029383943575232591

c2=49214988203490150276747792301684879609691422064251512890522970970218336932146528862589609901563805159513838522715709952369998123436173529247068824240517223714138280635390657679089482088947153346574540307858933121309172417967479865050170118505092890657743919149175571949857754691649010048255847728254165568453
n = 419916472265579552416321456467710878596763987166931063522673557495606283675831554079795103430745812693766299836367506776741843184218882108166483399079547437950982446158416014046045613948722430421219107806082834750037733159949680370048174996836635207474866849628635803749900592631958711112781563468286838317039

c = 164426087358257675255804735370423423165626405942957002285702794502161660220021926122067815801535996553342538800840955994047758964879166114971640453036464178633594026313984644255157877547994150992628436674467532930586961178014125039040425195475516069079684677782392389331381028625947642172607088177709582754314
def egcd(a, b):
    """
    Euclid's Extended GCD algorithm.
    https://en.wikipedia.org/wiki/Extended_Euclidean_algorithm
    """
    if a == 0:
        return b, 0, 1
    else:
        g, y, x = egcd(b % a, a)

    return g, x - (b // a) * y, y


def modinv(a, m):
    """
    Modular inverse using the e-GCD algorithm.
    https://en.wikipedia.org/wiki/Extended_Euclidean_algorithm#Computing_multiplicative_inverses_in_modular_structures
    """
    g, x, y = egcd(a, m)
    if g != 1:
        raise Exception('Modular inverse does not exist')
    else:
        return x % m


def findD(p, q):
    """Using p and q it find the d i.e private key for the decryption process
    """
    phi = (p-1)*(q-1)
    d = modinv(e, phi)
    return d


def secret1():
    """To get the first secret we simply find gcd of n1 and n2 and that gives us p.
     Then using n=p*q we can find q after that it's simple RSA to get the message
    """
    p = gcd(n1, n2)
    q1 = n1/p
    q2 = n2/p
    d1 = findD(p, q1)
    d2 = findD(p, q2)
    m1 = long_to_bytes(pow(c1, d1, n1))
    m2 = long_to_bytes(pow(c2, d2, n2))

    print(m1+m2)


def secret2(u, l, n):
    """We need to find p using this recursion function.

    In simpler word we divide n by 2 and take it as current p and then using
    q = next_prime(7*p) find the q. Then we do p*q and if we get the value to
    be equivalent to n that means it's the right p and q and we can move
    on decrypting the crypted message

    Arguments:
        u -- Upper bound
        l -- lower bound
        n -- modulus
    """

    diff = (u-l)/2
    hold = l+diff
    test = hold * next_prime(7*hold)

    if test == n:
        print("P is: ", hold)
        findM(hold)
    elif test > n:
        secret2(hold, l, n)
    elif test < n:
        secret2(u, hold, n)


def findM(p):
    """Find's the Message
    """
    q = next_prime(7*p)
    phi = (p-1)*(q-1)
    d = gmpy2.invert(mpz(str(e)),mpz(str(phi)))
    # m = pow(c, d, n)
    m = gmpy2.powmod(mpz(str(c)),d,mpz(str(n)))
    # print("Message in decimal: ",m)
    tmp = hex(m)
    print(tmp[2:].decode('hex'))

def main():
    if sys.argv[1] == "s1":
        secret1()
    elif sys.argv[1] == "s2":
        secret2(n/50, 0, n)


if __name__ == "__main__":
    main()
