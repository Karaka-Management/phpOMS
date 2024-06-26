<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System;

use phpOMS\Stdlib\Base\Enum;

/**
 * Mime type enum.
 *
 * Common mime types which can be helpful for responses where a specific mime needs to be set.
 *
 * @package phpOMS\System
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class MimeType extends Enum
{
    public const M_3DML = 'text/vnd.in3d.3dml';

    public const M_3DS = 'image/x-3ds';

    public const M_3G2 = 'video/3gpp2';

    public const M_3GP = 'video/3gpp';

    public const M_7Z = 'application/x-7z-compressed';

    public const M_AAB = 'application/x-authorware-bin';

    public const M_AAC = 'audio/x-aac';

    public const M_AAM = 'application/x-authorware-map';

    public const M_AAS = 'application/x-authorware-seg';

    public const M_ABW = 'application/x-abiword';

    public const M_AC = 'application/pkix-attr-cert';

    public const M_ACC = 'application/vnd.americandynamics.acc';

    public const M_ACE = 'application/x-ace-compressed';

    public const M_ACU = 'application/vnd.acucobol';

    public const M_ACUTC = 'application/vnd.acucorp';

    public const M_ADP = 'audio/adpcm';

    public const M_AEP = 'application/vnd.audiograph';

    public const M_AFM = 'application/x-font-type1';

    public const M_AFP = 'application/vnd.ibm.modcap';

    public const M_AHEAD = 'application/vnd.ahead.space';

    public const M_AI = 'application/postscript';

    public const M_AIF = 'audio/x-aiff';

    public const M_AIFC = 'audio/x-aiff';

    public const M_AIFF = 'audio/x-aiff';

    public const M_AIR = 'application/vnd.adobe.air-application-installer-package+zip';

    public const M_AIT = 'application/vnd.dvb.ait';

    public const M_AMI = 'application/vnd.amiga.ami';

    public const M_APK = 'application/vnd.android.package-archive';

    public const M_APPCACHE = 'text/cache-manifest';

    public const M_APR = 'application/vnd.lotus-approach';

    public const M_APS = 'application/postscript';

    public const M_ARC = 'application/x-freearc';

    public const M_ASC = 'application/pgp-signature';

    public const M_ASF = 'video/x-ms-asf';

    public const M_ASM = 'text/x-asm';

    public const M_ASO = 'application/vnd.accpac.simply.aso';

    public const M_ASX = 'video/x-ms-asf';

    public const M_ATC = 'application/vnd.acucorp';

    public const M_ATOM = 'application/atom+xml';

    public const M_ATOMCAT = 'application/atomcat+xml';

    public const M_ATOMSVC = 'application/atomsvc+xml';

    public const M_ATX = 'application/vnd.antix.game-component';

    public const M_AU = 'audio/basic';

    public const M_AVI = 'video/x-msvideo';

    public const M_AW = 'application/applixware';

    public const M_AZF = 'application/vnd.airzip.filesecure.azf';

    public const M_AZS = 'application/vnd.airzip.filesecure.azs';

    public const M_AZW = 'application/vnd.amazon.ebook';

    public const M_BAT = 'application/x-msdownload';

    public const M_BCPIO = 'application/x-bcpio';

    public const M_BDF = 'application/x-font-bdf';

    public const M_BDM = 'application/vnd.syncml.dm+wbxml';

    public const M_BED = 'application/vnd.realvnc.bed';

    public const M_BH2 = 'application/vnd.fujitsu.oasysprs';

    public const M_BIN = 'application/octet-stream';

    public const M_BLB = 'application/x-blorb';

    public const M_BLORB = 'application/x-blorb';

    public const M_BMI = 'application/vnd.bmi';

    public const M_BMP = 'image/bmp';

    public const M_BOOK = 'application/vnd.framemaker';

    public const M_BOX = 'application/vnd.previewsystems.box';

    public const M_BOZ = 'application/x-bzip2';

    public const M_BPK = 'application/octet-stream';

    public const M_BTIF = 'image/prs.btif';

    public const M_BZ = 'application/x-bzip';

    public const M_BZ2 = 'application/x-bzip2';

    public const M_C = 'text/x-c';

    public const M_C11AMC = 'application/vnd.cluetrust.cartomobile-config';

    public const M_C11AMZ = 'application/vnd.cluetrust.cartomobile-config-pkg';

    public const M_C4D = 'application/vnd.clonk.c4group';

    public const M_C4F = 'application/vnd.clonk.c4group';

    public const M_C4G = 'application/vnd.clonk.c4group';

    public const M_C4P = 'application/vnd.clonk.c4group';

    public const M_C4U = 'application/vnd.clonk.c4group';

    public const M_CAB = 'application/vnd.ms-cab-compressed';

    public const M_CAF = 'audio/x-caf';

    public const M_CAP = 'application/vnd.tcpdump.pcap';

    public const M_CAR = 'application/vnd.curl.car';

    public const M_CAT = 'application/vnd.ms-pki.seccat';

    public const M_CB7 = 'application/x-cbr';

    public const M_CBA = 'application/x-cbr';

    public const M_CBR = 'application/x-cbr';

    public const M_CBT = 'application/x-cbr';

    public const M_CBZ = 'application/x-cbr';

    public const M_CC = 'text/x-c';

    public const M_CCT = 'application/x-director';

    public const M_CCXML = 'application/ccxml+xml';

    public const M_CDBCMSG = 'application/vnd.contact.cmsg';

    public const M_CDF = 'application/x-netcdf';

    public const M_CDKEY = 'application/vnd.mediastation.cdkey';

    public const M_CDMIA = 'application/cdmi-capability';

    public const M_CDMIC = 'application/cdmi-container';

    public const M_CDMID = 'application/cdmi-domain';

    public const M_CDMIO = 'application/cdmi-object';

    public const M_CDMIQ = 'application/cdmi-queue';

    public const M_CDX = 'chemical/x-cdx';

    public const M_CDXML = 'application/vnd.chemdraw+xml';

    public const M_CDY = 'application/vnd.cinderella';

    public const M_CER = 'application/pkix-cert';

    public const M_CFS = 'application/x-cfs-compressed';

    public const M_CGM = 'image/cgm';

    public const M_CHAT = 'application/x-chat';

    public const M_CHM = 'application/vnd.ms-htmlhelp';

    public const M_CHRT = 'application/vnd.kde.kchart';

    public const M_CIF = 'chemical/x-cif';

    public const M_CII = 'application/vnd.anser-web-certificate-issue-initiation';

    public const M_CIL = 'application/vnd.ms-artgalry';

    public const M_CLA = 'application/vnd.claymore';

    public const M_CLASS = 'application/java-vm';

    public const M_CLKK = 'application/vnd.crick.clicker.keyboard';

    public const M_CLKP = 'application/vnd.crick.clicker.palette';

    public const M_CLKT = 'application/vnd.crick.clicker.template';

    public const M_CLKW = 'application/vnd.crick.clicker.wordbank';

    public const M_CLKX = 'application/vnd.crick.clicker';

    public const M_CLP = 'application/x-msclip';

    public const M_CMC = 'application/vnd.cosmocaller';

    public const M_CMDF = 'chemical/x-cmdf';

    public const M_CML = 'chemical/x-cml';

    public const M_CMP = 'application/vnd.yellowriver-custom-menu';

    public const M_CMX = 'image/x-cmx';

    public const M_COD = 'application/vnd.rim.cod';

    public const M_COM = 'application/x-msdownload';

    public const M_CONF = 'text/plain';

    public const M_CPIO = 'application/x-cpio';

    public const M_CPP = 'text/x-c';

    public const M_CPT = 'application/mac-compactpro';

    public const M_CRD = 'application/x-mscardfile';

    public const M_CRL = 'application/pkix-crl';

    public const M_CRT = 'application/x-x509-ca-cert';

    public const M_CSH = 'application/x-csh';

    public const M_CSML = 'chemical/x-csml';

    public const M_CSP = 'application/vnd.commonspace';

    public const M_CSS = 'text/css';

    public const M_CST = 'application/x-director';

    public const M_CSV = 'text/csv';

    public const M_CU = 'application/cu-seeme';

    public const M_CURL = 'text/vnd.curl';

    public const M_CWW = 'application/prs.cww';

    public const M_CXT = 'application/x-director';

    public const M_CXX = 'text/x-c';

    public const M_DAE = 'model/vnd.collada+xml';

    public const M_DAF = 'application/vnd.mobius.daf';

    public const M_DART = 'application/vnd.dart';

    public const M_DATALESS = 'application/vnd.fdsn.seed';

    public const M_DAVMOUNT = 'application/davmount+xml';

    public const M_DBK = 'application/docbook+xml';

    public const M_DCR = 'application/x-director';

    public const M_DCURL = 'text/vnd.curl.dcurl';

    public const M_DD2 = 'application/vnd.oma.dd2+xml';

    public const M_DDD = 'application/vnd.fujixerox.ddd';

    public const M_DEB = 'application/x-debian-package';

    public const M_DEF = 'text/plain';

    public const M_DEPLOY = 'application/octet-stream';

    public const M_DER = 'application/x-x509-ca-cert';

    public const M_DFAC = 'application/vnd.dreamfactory';

    public const M_DGC = 'application/x-dgc-compressed';

    public const M_DIC = 'text/x-c';

    public const M_DIR = 'application/x-director';

    public const M_DIS = 'application/vnd.mobius.dis';

    public const M_DIST = 'application/octet-stream';

    public const M_DISTZ = 'application/octet-stream';

    public const M_DJV = 'image/vnd.djvu';

    public const M_DJVU = 'image/vnd.djvu';

    public const M_DLL = 'application/x-msdownload';

    public const M_DMG = 'application/x-apple-diskimage';

    public const M_DMP = 'application/vnd.tcpdump.pcap';

    public const M_DMS = 'application/octet-stream';

    public const M_DNA = 'application/vnd.dna';

    public const M_DOC = 'application/msword';

    public const M_DOCM = 'application/vnd.ms-word.document.macroenabled.12';

    public const M_DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

    public const M_DOT = 'application/msword';

    public const M_DOTM = 'application/vnd.ms-word.template.macroenabled.12';

    public const M_DOTX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';

    public const M_DP = 'application/vnd.osgi.dp';

    public const M_DPG = 'application/vnd.dpgraph';

    public const M_DRA = 'audio/vnd.dra';

    public const M_DSC = 'text/prs.lines.tag';

    public const M_DSSC = 'application/dssc+der';

    public const M_DTB = 'application/x-dtbook+xml';

    public const M_DTD = 'application/xml-dtd';

    public const M_DTS = 'audio/vnd.dts';

    public const M_DTSHD = 'audio/vnd.dts.hd';

    public const M_DUMP = 'application/octet-stream';

    public const M_DVB = 'video/vnd.dvb.file';

    public const M_DVI = 'application/x-dvi';

    public const M_DWF = 'model/vnd.dwf';

    public const M_DWG = 'image/vnd.dwg';

    public const M_DXF = 'image/vnd.dxf';

    public const M_DXP = 'application/vnd.spotfire.dxp';

    public const M_DXR = 'application/x-director';

    public const M_ECELP4800 = 'audio/vnd.nuera.ecelp4800';

    public const M_ECELP7470 = 'audio/vnd.nuera.ecelp7470';

    public const M_ECELP9600 = 'audio/vnd.nuera.ecelp9600';

    public const M_ECMA = 'application/ecmascript';

    public const M_EDM = 'application/vnd.novadigm.edm';

    public const M_EDX = 'application/vnd.novadigm.edx';

    public const M_EFIF = 'application/vnd.picsel';

    public const M_EI6 = 'application/vnd.pg.osasli';

    public const M_ELC = 'application/octet-stream';

    public const M_EMF = 'application/x-msmetafile';

    public const M_EML = 'message/rfc822';

    public const M_EMMA = 'application/emma+xml';

    public const M_EMZ = 'application/x-msmetafile';

    public const M_EOL = 'audio/vnd.digital-winds';

    public const M_EOT = 'application/vnd.ms-fontobject';

    public const M_EPS = 'application/postscript';

    public const M_EPUB = 'application/epub+zip';

    public const M_ES3 = 'application/vnd.eszigno3+xml';

    public const M_ESA = 'application/vnd.osgi.subsystem';

    public const M_ESF = 'application/vnd.epson.esf';

    public const M_ET3 = 'application/vnd.eszigno3+xml';

    public const M_ETX = 'text/x-setext';

    public const M_EVA = 'application/x-eva';

    public const M_EVY = 'application/x-envoy';

    public const M_EXI = 'application/exi';

    public const M_EXT = 'application/vnd.novadigm.ext';

    public const M_EZ = 'application/andrew-inset';

    public const M_EZ2 = 'application/vnd.ezpix-album';

    public const M_EZ3 = 'application/vnd.ezpix-package';

    public const M_F = 'text/x-fortran';

    public const M_F4V = 'video/x-f4v';

    public const M_F77 = 'text/x-fortran';

    public const M_F90 = 'text/x-fortran';

    public const M_FBS = 'image/vnd.fastbidsheet';

    public const M_FCDT = 'application/vnd.adobe.formscentral.fcdt';

    public const M_FCS = 'application/vnd.isac.fcs';

    public const M_FDF = 'application/vnd.fdf';

    public const M_FE_LAUNCH = 'application/vnd.denovo.fcselayout-link';

    public const M_FG5 = 'application/vnd.fujitsu.oasysgp';

    public const M_FGD = 'application/x-director';

    public const M_FH = 'image/x-freehand';

    public const M_FH4 = 'image/x-freehand';

    public const M_FH5 = 'image/x-freehand';

    public const M_FH7 = 'image/x-freehand';

    public const M_FHC = 'image/x-freehand';

    public const M_FIG = 'application/x-xfig';

    public const M_FLAC = 'audio/x-flac';

    public const M_FLI = 'video/x-fli';

    public const M_FLO = 'application/vnd.micrografx.flo';

    public const M_FLV = 'video/x-flv';

    public const M_FLW = 'application/vnd.kde.kivio';

    public const M_FLX = 'text/vnd.fmi.flexstor';

    public const M_FLY = 'text/vnd.fly';

    public const M_FM = 'application/vnd.framemaker';

    public const M_FNC = 'application/vnd.frogans.fnc';

    public const M_FOR = 'text/x-fortran';

    public const M_FPX = 'image/vnd.fpx';

    public const M_FRAME = 'application/vnd.framemaker';

    public const M_FSC = 'application/vnd.fsc.weblaunch';

    public const M_FST = 'image/vnd.fst';

    public const M_FTC = 'application/vnd.fluxtime.clip';

    public const M_FTI = 'application/vnd.anser-web-funds-transfer-initiation';

    public const M_FVT = 'video/vnd.fvt';

    public const M_FXP = 'application/vnd.adobe.fxp';

    public const M_FXPL = 'application/vnd.adobe.fxp';

    public const M_FZS = 'application/vnd.fuzzysheet';

    public const M_G2W = 'application/vnd.geoplan';

    public const M_G3 = 'image/g3fax';

    public const M_G3W = 'application/vnd.geospace';

    public const M_GAC = 'application/vnd.groove-account';

    public const M_GAM = 'application/x-tads';

    public const M_GBR = 'application/rpki-ghostbusters';

    public const M_GCA = 'application/x-gca-compressed';

    public const M_GDL = 'model/vnd.gdl';

    public const M_GEO = 'application/vnd.dynageo';

    public const M_GEX = 'application/vnd.geometry-explorer';

    public const M_GGB = 'application/vnd.geogebra.file';

    public const M_GGT = 'application/vnd.geogebra.tool';

    public const M_GHF = 'application/vnd.groove-help';

    public const M_GIF = 'image/gif';

    public const M_GIM = 'application/vnd.groove-identity-message';

    public const M_GML = 'application/gml+xml';

    public const M_GMX = 'application/vnd.gmx';

    public const M_GNUMERIC = 'application/x-gnumeric';

    public const M_GPH = 'application/vnd.flographit';

    public const M_GPX = 'application/gpx+xml';

    public const M_GQF = 'application/vnd.grafeq';

    public const M_GQS = 'application/vnd.grafeq';

    public const M_GRAM = 'application/srgs';

    public const M_GRAMPS = 'application/x-gramps-xml';

    public const M_GRE = 'application/vnd.geometry-explorer';

    public const M_GRV = 'application/vnd.groove-injector';

    public const M_GRXML = 'application/srgs+xml';

    public const M_GSF = 'application/x-font-ghostscript';

    public const M_GTAR = 'application/x-gtar';

    public const M_GTM = 'application/vnd.groove-tool-message';

    public const M_GTW = 'model/vnd.gtw';

    public const M_GV = 'text/vnd.graphviz';

    public const M_GXF = 'application/gxf';

    public const M_GXT = 'application/vnd.geonext';

    public const M_GZ = 'application/x-gzip';

    public const M_H = 'text/x-c';

    public const M_H261 = 'video/h261';

    public const M_H263 = 'video/h263';

    public const M_H264 = 'video/h264';

    public const M_HAL = 'application/vnd.hal+xml';

    public const M_HBCI = 'application/vnd.hbci';

    public const M_HDF = 'application/x-hdf';

    public const M_HH = 'text/x-c';

    public const M_HLP = 'application/winhlp';

    public const M_HPGL = 'application/vnd.hp-hpgl';

    public const M_HPID = 'application/vnd.hp-hpid';

    public const M_HPS = 'application/vnd.hp-hps';

    public const M_HQX = 'application/mac-binhex40';

    public const M_HTKE = 'application/vnd.kenameaapp';

    public const M_HTM = 'text/html';

    public const M_HTML = 'text/html';

    public const M_HVD = 'application/vnd.yamaha.hv-dic';

    public const M_HVP = 'application/vnd.yamaha.hv-voice';

    public const M_HVS = 'application/vnd.yamaha.hv-script';

    public const M_I2G = 'application/vnd.intergeo';

    public const M_ICC = 'application/vnd.iccprofile';

    public const M_ICE = 'x-conference/x-cooltalk';

    public const M_ICM = 'application/vnd.iccprofile';

    public const M_ICO = 'image/x-icon';

    public const M_ICS = 'text/calendar';

    public const M_IEF = 'image/ief';

    public const M_IFB = 'text/calendar';

    public const M_IFM = 'application/vnd.shana.informed.formdata';

    public const M_IGES = 'model/iges';

    public const M_IGL = 'application/vnd.igloader';

    public const M_IGM = 'application/vnd.insors.igm';

    public const M_IGS = 'model/iges';

    public const M_IGX = 'application/vnd.micrografx.igx';

    public const M_IIF = 'application/vnd.shana.informed.interchange';

    public const M_IMP = 'application/vnd.accpac.simply.imp';

    public const M_IMS = 'application/vnd.ms-ims';

    public const M_IN = 'text/plain';

    public const M_INK = 'application/inkml+xml';

    public const M_INKML = 'application/inkml+xml';

    public const M_INSTALL = 'application/x-install-instructions';

    public const M_IOTA = 'application/vnd.astraea-software.iota';

    public const M_IPFIX = 'application/ipfix';

    public const M_IPK = 'application/vnd.shana.informed.package';

    public const M_IRM = 'application/vnd.ibm.rights-management';

    public const M_IRP = 'application/vnd.irepository.package+xml';

    public const M_ISO = 'application/x-iso9660-image';

    public const M_ITP = 'application/vnd.shana.informed.formtemplate';

    public const M_IVP = 'application/vnd.immervision-ivp';

    public const M_IVU = 'application/vnd.immervision-ivu';

    public const M_JAD = 'text/vnd.sun.j2me.app-descriptor';

    public const M_JAM = 'application/vnd.jam';

    public const M_JAR = 'application/java-archive';

    public const M_JAVA = 'text/x-java-source';

    public const M_JISP = 'application/vnd.jisp';

    public const M_JLT = 'application/vnd.hp-jlyt';

    public const M_JNLP = 'application/x-java-jnlp-file';

    public const M_JODA = 'application/vnd.joost.joda-archive';

    public const M_JPE = 'image/jpeg';

    public const M_JPEG = 'image/jpeg';

    public const M_JPG = 'image/jpeg';

    public const M_JPGM = 'video/jpm';

    public const M_JPGV = 'video/jpeg';

    public const M_JPM = 'video/jpm';

    public const M_JS = 'application/javascript';

    public const M_JSON = 'application/json';

    public const M_JSONML = 'application/jsonml+json';

    public const M_KAR = 'audio/midi';

    public const M_KARBON = 'application/vnd.kde.karbon';

    public const M_KFO = 'application/vnd.kde.kformula';

    public const M_KIA = 'application/vnd.kidspiration';

    public const M_KML = 'application/vnd.google-earth.kml+xml';

    public const M_KMZ = 'application/vnd.google-earth.kmz';

    public const M_KNE = 'application/vnd.kinar';

    public const M_KNP = 'application/vnd.kinar';

    public const M_KON = 'application/vnd.kde.kontour';

    public const M_KPR = 'application/vnd.kde.kpresenter';

    public const M_KPT = 'application/vnd.kde.kpresenter';

    public const M_KPXX = 'application/vnd.ds-keypoint';

    public const M_KSP = 'application/vnd.kde.kspread';

    public const M_KTR = 'application/vnd.kahootz';

    public const M_KTX = 'image/ktx';

    public const M_KTZ = 'application/vnd.kahootz';

    public const M_KWD = 'application/vnd.kde.kword';

    public const M_KWT = 'application/vnd.kde.kword';

    public const M_LASXML = 'application/vnd.las.las+xml';

    public const M_LATEX = 'application/x-latex';

    public const M_LBD = 'application/vnd.llamagraphics.life-balance.desktop';

    public const M_LBE = 'application/vnd.llamagraphics.life-balance.exchange+xml';

    public const M_LES = 'application/vnd.hhe.lesson-player';

    public const M_LHA = 'application/x-lzh-compressed';

    public const M_LINK66 = 'application/vnd.route66.link66+xml';

    public const M_LIST = 'text/plain';

    public const M_LIST3820 = 'application/vnd.ibm.modcap';

    public const M_LISTAFP = 'application/vnd.ibm.modcap';

    public const M_LNK = 'application/x-ms-shortcut';

    public const M_LOG = 'text/plain';

    public const M_LOSTXML = 'application/lost+xml';

    public const M_LRF = 'application/octet-stream';

    public const M_LRM = 'application/vnd.ms-lrm';

    public const M_LTF = 'application/vnd.frogans.ltf';

    public const M_LVP = 'audio/vnd.lucent.voice';

    public const M_LWP = 'application/vnd.lotus-wordpro';

    public const M_LZH = 'application/x-lzh-compressed';

    public const M_M13 = 'application/x-msmediaview';

    public const M_M14 = 'application/x-msmediaview';

    public const M_M1V = 'video/mpeg';

    public const M_M21 = 'application/mp21';

    public const M_M2A = 'audio/mpeg';

    public const M_M2V = 'video/mpeg';

    public const M_M3A = 'audio/mpeg';

    public const M_M3U = 'audio/x-mpegurl';

    public const M_M3U8 = 'application/vnd.apple.mpegurl';

    public const M_M4A = 'audio/mp4';

    public const M_M4U = 'video/vnd.mpegurl';

    public const M_M4V = 'video/x-m4v';

    public const M_MA = 'application/mathematica';

    public const M_MADS = 'application/mads+xml';

    public const M_MAG = 'application/vnd.ecowin.chart';

    public const M_MAKER = 'application/vnd.framemaker';

    public const M_MAN = 'text/troff';

    public const M_MAR = 'application/octet-stream';

    public const M_MATHML = 'application/mathml+xml';

    public const M_MB = 'application/mathematica';

    public const M_MBK = 'application/vnd.mobius.mbk';

    public const M_MBOX = 'application/mbox';

    public const M_MC1 = 'application/vnd.medcalcdata';

    public const M_MCD = 'application/vnd.mcd';

    public const M_MCURL = 'text/vnd.curl.mcurl';

    public const M_MDB = 'application/x-msaccess';

    public const M_MDI = 'image/vnd.ms-modi';

    public const M_ME = 'text/troff';

    public const M_MESH = 'model/mesh';

    public const M_META4 = 'application/metalink4+xml';

    public const M_METALINK = 'application/metalink+xml';

    public const M_METS = 'application/mets+xml';

    public const M_MFM = 'application/vnd.mfmp';

    public const M_MFT = 'application/rpki-manifest';

    public const M_MGP = 'application/vnd.osgeo.mapguide.package';

    public const M_MGZ = 'application/vnd.proteus.magazine';

    public const M_MID = 'audio/midi';

    public const M_MIDI = 'audio/midi';

    public const M_MIE = 'application/x-mie';

    public const M_MIF = 'application/vnd.mif';

    public const M_MIME = 'message/rfc822';

    public const M_MJ2 = 'video/mj2';

    public const M_MJP2 = 'video/mj2';

    public const M_MK3D = 'video/x-matroska';

    public const M_MKA = 'audio/x-matroska';

    public const M_MKS = 'video/x-matroska';

    public const M_MKV = 'video/x-matroska';

    public const M_MLP = 'application/vnd.dolby.mlp';

    public const M_MMD = 'application/vnd.chipnuts.karaoke-mmd';

    public const M_MMF = 'application/vnd.smaf';

    public const M_MMR = 'image/vnd.fujixerox.edmics-mmr';

    public const M_MNG = 'video/x-mng';

    public const M_MNY = 'application/x-msmoney';

    public const M_MOBI = 'application/x-mobipocket-ebook';

    public const M_MODS = 'application/mods+xml';

    public const M_MOV = 'video/quicktime';

    public const M_MOVIE = 'video/x-sgi-movie';

    public const M_MP2 = 'audio/mpeg';

    public const M_MP21 = 'application/mp21';

    public const M_MP2A = 'audio/mpeg';

    public const M_MP3 = 'audio/mpeg';

    public const M_MP4 = 'video/mp4';

    public const M_MP4A = 'audio/mp4';

    public const M_MP4S = 'application/mp4';

    public const M_MP4V = 'video/mp4';

    public const M_MPC = 'application/vnd.mophun.certificate';

    public const M_MPE = 'video/mpeg';

    public const M_MPEG = 'video/mpeg';

    public const M_MPG = 'video/mpeg';

    public const M_MPG4 = 'video/mp4';

    public const M_MPGA = 'audio/mpeg';

    public const M_MPKG = 'application/vnd.apple.installer+xml';

    public const M_MPM = 'application/vnd.blueice.multipass';

    public const M_MPN = 'application/vnd.mophun.application';

    public const M_MPP = 'application/vnd.ms-project';

    public const M_MPT = 'application/vnd.ms-project';

    public const M_MPY = 'application/vnd.ibm.minipay';

    public const M_MQY = 'application/vnd.mobius.mqy';

    public const M_MRC = 'application/marc';

    public const M_MRCX = 'application/marcxml+xml';

    public const M_MS = 'text/troff';

    public const M_MSCML = 'application/mediaservercontrol+xml';

    public const M_MSEED = 'application/vnd.fdsn.mseed';

    public const M_MSEQ = 'application/vnd.mseq';

    public const M_MSF = 'application/vnd.epson.msf';

    public const M_MSH = 'model/mesh';

    public const M_MSI = 'application/x-msdownload';

    public const M_MSL = 'application/vnd.mobius.msl';

    public const M_MSTY = 'application/vnd.muvee.style';

    public const M_MULT = 'multipart/form-data';

    public const M_ALT = 'multipart/alternative';

    public const M_MIXED = 'multipart/mixed';

    public const M_RELATED = 'multipart/related';

    public const M_MTS = 'model/vnd.mts';

    public const M_MUS = 'application/vnd.musician';

    public const M_MUSICXML = 'application/vnd.recordare.musicxml+xml';

    public const M_MVB = 'application/x-msmediaview';

    public const M_MWF = 'application/vnd.mfer';

    public const M_MXF = 'application/mxf';

    public const M_MXL = 'application/vnd.recordare.musicxml';

    public const M_MXML = 'application/xv+xml';

    public const M_MXS = 'application/vnd.triscape.mxs';

    public const M_MXU = 'video/vnd.mpegurl';

    public const M_N_GAGE = 'application/vnd.nokia.n-gage.symbian.install';

    public const M_N3 = 'text/n3';

    public const M_NB = 'application/mathematica';

    public const M_NBP = 'application/vnd.wolfram.player';

    public const M_NC = 'application/x-netcdf';

    public const M_NCX = 'application/x-dtbncx+xml';

    public const M_NFO = 'text/x-nfo';

    public const M_NGDAT = 'application/vnd.nokia.n-gage.data';

    public const M_NITF = 'application/vnd.nitf';

    public const M_NLU = 'application/vnd.neurolanguage.nlu';

    public const M_NML = 'application/vnd.enliven';

    public const M_NND = 'application/vnd.noblenet-directory';

    public const M_NNS = 'application/vnd.noblenet-sealer';

    public const M_NNW = 'application/vnd.noblenet-web';

    public const M_NPX = 'image/vnd.net-fpx';

    public const M_NSC = 'application/x-conference';

    public const M_NSF = 'application/vnd.lotus-notes';

    public const M_NTF = 'application/vnd.nitf';

    public const M_NZB = 'application/x-nzb';

    public const M_OA2 = 'application/vnd.fujitsu.oasys2';

    public const M_OA3 = 'application/vnd.fujitsu.oasys3';

    public const M_OAS = 'application/vnd.fujitsu.oasys';

    public const M_OBD = 'application/x-msbinder';

    public const M_OBJ = 'application/x-tgif';

    public const M_ODA = 'application/oda';

    public const M_ODB = 'application/vnd.oasis.opendocument.database';

    public const M_ODC = 'application/vnd.oasis.opendocument.chart';

    public const M_ODF = 'application/vnd.oasis.opendocument.formula';

    public const M_ODFT = 'application/vnd.oasis.opendocument.formula-template';

    public const M_ODG = 'application/vnd.oasis.opendocument.graphics';

    public const M_ODI = 'application/vnd.oasis.opendocument.image';

    public const M_ODM = 'application/vnd.oasis.opendocument.text-master';

    public const M_ODP = 'application/vnd.oasis.opendocument.presentation';

    public const M_ODS = 'application/vnd.oasis.opendocument.spreadsheet';

    public const M_ODT = 'application/vnd.oasis.opendocument.text';

    public const M_OGA = 'audio/ogg';

    public const M_OGG = 'audio/ogg';

    public const M_OGV = 'video/ogg';

    public const M_OGX = 'application/ogg';

    public const M_OMDOC = 'application/omdoc+xml';

    public const M_ONEPKG = 'application/onenote';

    public const M_ONETMP = 'application/onenote';

    public const M_ONETOC = 'application/onenote';

    public const M_ONETOC2 = 'application/onenote';

    public const M_OPF = 'application/oebps-package+xml';

    public const M_OPML = 'text/x-opml';

    public const M_OPRC = 'application/vnd.palm';

    public const M_ORG = 'application/vnd.lotus-organizer';

    public const M_OSF = 'application/vnd.yamaha.openscoreformat';

    public const M_OSFPVG = 'application/vnd.yamaha.openscoreformat.osfpvg+xml';

    public const M_OTC = 'application/vnd.oasis.opendocument.chart-template';

    public const M_OTF = 'application/x-font-otf';

    public const M_OTG = 'application/vnd.oasis.opendocument.graphics-template';

    public const M_OTH = 'application/vnd.oasis.opendocument.text-web';

    public const M_OTI = 'application/vnd.oasis.opendocument.image-template';

    public const M_OTP = 'application/vnd.oasis.opendocument.presentation-template';

    public const M_OTS = 'application/vnd.oasis.opendocument.spreadsheet-template';

    public const M_OTT = 'application/vnd.oasis.opendocument.text-template';

    public const M_OXPS = 'application/oxps';

    public const M_OXT = 'application/vnd.openofficeorg.extension';

    public const M_P = 'text/x-pascal';

    public const M_P10 = 'application/pkcs10';

    public const M_P12 = 'application/x-pkcs12';

    public const M_P7B = 'application/x-pkcs7-certificates';

    public const M_P7C = 'application/pkcs7-mime';

    public const M_P7M = 'application/pkcs7-mime';

    public const M_P7R = 'application/x-pkcs7-certreqresp';

    public const M_P7S = 'application/pkcs7-signature';

    public const M_P8 = 'application/pkcs8';

    public const M_PAS = 'text/x-pascal';

    public const M_PAW = 'application/vnd.pawaafile';

    public const M_PBD = 'application/vnd.powerbuilder6';

    public const M_PBM = 'image/x-portable-bitmap';

    public const M_PCAP = 'application/vnd.tcpdump.pcap';

    public const M_PCF = 'application/x-font-pcf';

    public const M_PCL = 'application/vnd.hp-pcl';

    public const M_PCLXL = 'application/vnd.hp-pclxl';

    public const M_PCT = 'image/x-pict';

    public const M_PCURL = 'application/vnd.curl.pcurl';

    public const M_PCX = 'image/x-pcx';

    public const M_PDB = 'application/vnd.palm';

    public const M_PDF = 'application/pdf';

    public const M_PFA = 'application/x-font-type1';

    public const M_PFB = 'application/x-font-type1';

    public const M_PFM = 'application/x-font-type1';

    public const M_PFR = 'application/font-tdpfr';

    public const M_PFX = 'application/x-pkcs12';

    public const M_PGM = 'image/x-portable-graymap';

    public const M_PGN = 'application/x-chess-pgn';

    public const M_PGP = 'application/pgp-encrypted';

    public const M_PHP = 'application/x-php';

    public const M_PHP3 = 'application/x-php';

    public const M_PHP4 = 'application/x-php';

    public const M_PHP5 = 'application/x-php';

    public const M_PIC = 'image/x-pict';

    public const M_PKG = 'application/octet-stream';

    public const M_PKI = 'application/pkixcmp';

    public const M_PKIPATH = 'application/pkix-pkipath';

    public const M_PLB = 'application/vnd.3gpp.pic-bw-large';

    public const M_PLC = 'application/vnd.mobius.plc';

    public const M_PLF = 'application/vnd.pocketlearn';

    public const M_PLS = 'application/pls+xml';

    public const M_PML = 'application/vnd.ctc-posml';

    public const M_PNG = 'image/png';

    public const M_PNM = 'image/x-portable-anymap';

    public const M_PORTPKG = 'application/vnd.macports.portpkg';

    public const M_POST = 'application/x-www-form-urlencoded';

    public const M_POT = 'application/vnd.ms-powerpoint';

    public const M_POTM = 'application/vnd.ms-powerpoint.template.macroenabled.12';

    public const M_POTX = 'application/vnd.openxmlformats-officedocument.presentationml.template';

    public const M_PPAM = 'application/vnd.ms-powerpoint.addin.macroenabled.12';

    public const M_PPD = 'application/vnd.cups-ppd';

    public const M_PPM = 'image/x-portable-pixmap';

    public const M_PPS = 'application/vnd.ms-powerpoint';

    public const M_PPSM = 'application/vnd.ms-powerpoint.slideshow.macroenabled.12';

    public const M_PPSX = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';

    public const M_PPT = 'application/vnd.ms-powerpoint';

    public const M_PPTM = 'application/vnd.ms-powerpoint.presentation.macroenabled.12';

    public const M_PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';

    public const M_PQA = 'application/vnd.palm';

    public const M_PRC = 'application/x-mobipocket-ebook';

    public const M_PRE = 'application/vnd.lotus-freelance';

    public const M_PRF = 'application/pics-rules';

    public const M_PS = 'application/postscript';

    public const M_PSB = 'application/vnd.3gpp.pic-bw-small';

    public const M_PSD = 'image/vnd.adobe.photoshop';

    public const M_PSF = 'application/x-font-linux-psf';

    public const M_PSKCXML = 'application/pskc+xml';

    public const M_PTID = 'application/vnd.pvi.ptid1';

    public const M_PUB = 'application/x-mspublisher';

    public const M_PVB = 'application/vnd.3gpp.pic-bw-var';

    public const M_PWN = 'application/vnd.3m.post-it-notes';

    public const M_PYA = 'audio/vnd.ms-playready.media.pya';

    public const M_PYV = 'video/vnd.ms-playready.media.pyv';

    public const M_QAM = 'application/vnd.epson.quickanime';

    public const M_QBO = 'application/vnd.intu.qbo';

    public const M_QFX = 'application/vnd.intu.qfx';

    public const M_QPS = 'application/vnd.publishare-delta-tree';

    public const M_QT = 'video/quicktime';

    public const M_QWD = 'application/vnd.quark.quarkxpress';

    public const M_QWT = 'application/vnd.quark.quarkxpress';

    public const M_QXB = 'application/vnd.quark.quarkxpress';

    public const M_QXD = 'application/vnd.quark.quarkxpress';

    public const M_QXL = 'application/vnd.quark.quarkxpress';

    public const M_QXT = 'application/vnd.quark.quarkxpress';

    public const M_RA = 'audio/x-pn-realaudio';

    public const M_RAM = 'audio/x-pn-realaudio';

    public const M_RAR = 'application/x-rar-compressed';

    public const M_RAS = 'image/x-cmu-raster';

    public const M_RCPROFILE = 'application/vnd.ipunplugged.rcprofile';

    public const M_RDF = 'application/rdf+xml';

    public const M_RDZ = 'application/vnd.data-vision.rdz';

    public const M_REP = 'application/vnd.businessobjects';

    public const M_RES = 'application/x-dtbresource+xml';

    public const M_RGB = 'image/x-rgb';

    public const M_RIF = 'application/reginfo+xml';

    public const M_RIP = 'audio/vnd.rip';

    public const M_RIS = 'application/x-research-info-systems';

    public const M_RL = 'application/resource-lists+xml';

    public const M_RLC = 'image/vnd.fujixerox.edmics-rlc';

    public const M_RLD = 'application/resource-lists-diff+xml';

    public const M_RM = 'application/vnd.rn-realmedia';

    public const M_RMI = 'audio/midi';

    public const M_RMP = 'audio/x-pn-realaudio-plugin';

    public const M_RMS = 'application/vnd.jcp.javame.midlet-rms';

    public const M_RMVB = 'application/vnd.rn-realmedia-vbr';

    public const M_RNC = 'application/relax-ng-compact-syntax';

    public const M_ROA = 'application/rpki-roa';

    public const M_ROFF = 'text/troff';

    public const M_RP9 = 'application/vnd.cloanto.rp9';

    public const M_RPSS = 'application/vnd.nokia.radio-presets';

    public const M_RPST = 'application/vnd.nokia.radio-preset';

    public const M_RQ = 'application/sparql-query';

    public const M_RS = 'application/rls-services+xml';

    public const M_RSD = 'application/rsd+xml';

    public const M_RSS = 'application/rss+xml';

    public const M_RTF = 'application/rtf';

    public const M_RTX = 'text/richtext';

    public const M_S = 'text/x-asm';

    public const M_S3M = 'audio/s3m';

    public const M_SAF = 'application/vnd.yamaha.smaf-audio';

    public const M_SBML = 'application/sbml+xml';

    public const M_SC = 'application/vnd.ibm.secure-container';

    public const M_SCD = 'application/x-msschedule';

    public const M_SCM = 'application/vnd.lotus-screencam';

    public const M_SCQ = 'application/scvp-cv-request';

    public const M_SCS = 'application/scvp-cv-response';

    public const M_SCURL = 'text/vnd.curl.scurl';

    public const M_SDA = 'application/vnd.stardivision.draw';

    public const M_SDC = 'application/vnd.stardivision.calc';

    public const M_SDD = 'application/vnd.stardivision.impress';

    public const M_SDKD = 'application/vnd.solent.sdkm+xml';

    public const M_SDKM = 'application/vnd.solent.sdkm+xml';

    public const M_SDP = 'application/sdp';

    public const M_SDW = 'application/vnd.stardivision.writer';

    public const M_SEE = 'application/vnd.seemail';

    public const M_SEED = 'application/vnd.fdsn.seed';

    public const M_SEMA = 'application/vnd.sema';

    public const M_SEMD = 'application/vnd.semd';

    public const M_SEMF = 'application/vnd.semf';

    public const M_SER = 'application/java-serialized-object';

    public const M_SETPAY = 'application/set-payment-initiation';

    public const M_SETREG = 'application/set-registration-initiation';

    public const M_SFD_HDSTX = 'application/vnd.hydrostatix.sof-data';

    public const M_SFS = 'application/vnd.spotfire.sfs';

    public const M_SFV = 'text/x-sfv';

    public const M_SGI = 'image/sgi';

    public const M_SGL = 'application/vnd.stardivision.writer-global';

    public const M_SGM = 'text/sgml';

    public const M_SGML = 'text/sgml';

    public const M_SH = 'application/x-sh';

    public const M_SHAR = 'application/x-shar';

    public const M_SHF = 'application/shf+xml';

    public const M_SID = 'image/x-mrsid-image';

    public const M_SIG = 'application/pgp-signature';

    public const M_SIL = 'audio/silk';

    public const M_SILO = 'model/mesh';

    public const M_SIS = 'application/vnd.symbian.install';

    public const M_SISX = 'application/vnd.symbian.install';

    public const M_SIT = 'application/x-stuffit';

    public const M_SITX = 'application/x-stuffitx';

    public const M_SKD = 'application/vnd.koan';

    public const M_SKM = 'application/vnd.koan';

    public const M_SKP = 'application/vnd.koan';

    public const M_SKT = 'application/vnd.koan';

    public const M_SLDM = 'application/vnd.ms-powerpoint.slide.macroenabled.12';

    public const M_SLDX = 'application/vnd.openxmlformats-officedocument.presentationml.slide';

    public const M_SLT = 'application/vnd.epson.salt';

    public const M_SM = 'application/vnd.stepmania.stepchart';

    public const M_SMF = 'application/vnd.stardivision.math';

    public const M_SMI = 'application/smil+xml';

    public const M_SMIL = 'application/smil+xml';

    public const M_SMV = 'video/x-smv';

    public const M_SMZIP = 'application/vnd.stepmania.package';

    public const M_SND = 'audio/basic';

    public const M_SNF = 'application/x-font-snf';

    public const M_SO = 'application/octet-stream';

    public const M_SPC = 'application/x-pkcs7-certificates';

    public const M_SPF = 'application/vnd.yamaha.smaf-phrase';

    public const M_SPL = 'application/x-futuresplash';

    public const M_SPOT = 'text/vnd.in3d.spot';

    public const M_SPP = 'application/scvp-vp-response';

    public const M_SPQ = 'application/scvp-vp-request';

    public const M_SPX = 'audio/ogg';

    public const M_SQL = 'application/x-sql';

    public const M_SRC = 'application/x-wais-source';

    public const M_SRT = 'application/x-subrip';

    public const M_SRU = 'application/sru+xml';

    public const M_SRX = 'application/sparql-results+xml';

    public const M_SSDL = 'application/ssdl+xml';

    public const M_SSE = 'application/vnd.kodak-descriptor';

    public const M_SSF = 'application/vnd.epson.ssf';

    public const M_SSML = 'application/ssml+xml';

    public const M_ST = 'application/vnd.sailingtracker.track';

    public const M_STC = 'application/vnd.sun.xml.calc.template';

    public const M_STD = 'application/vnd.sun.xml.draw.template';

    public const M_STF = 'application/vnd.wt.stf';

    public const M_STI = 'application/vnd.sun.xml.impress.template';

    public const M_STK = 'application/hyperstudio';

    public const M_STL = 'application/vnd.ms-pki.stl';

    public const M_STR = 'application/vnd.pg.format';

    public const M_STW = 'application/vnd.sun.xml.writer.template';

    public const M_SUB = 'text/vnd.dvb.subtitle';

    public const M_SUS = 'application/vnd.sus-calendar';

    public const M_SUSP = 'application/vnd.sus-calendar';

    public const M_SV4CPIO = 'application/x-sv4cpio';

    public const M_SV4CRC = 'application/x-sv4crc';

    public const M_SVC = 'application/vnd.dvb.service';

    public const M_SVD = 'application/vnd.svd';

    public const M_SVG = 'image/svg+xml';

    public const M_SVGZ = 'image/svg+xml';

    public const M_SWA = 'application/x-director';

    public const M_SWF = 'application/x-shockwave-flash';

    public const M_SWI = 'application/vnd.aristanetworks.swi';

    public const M_SXC = 'application/vnd.sun.xml.calc';

    public const M_SXD = 'application/vnd.sun.xml.draw';

    public const M_SXG = 'application/vnd.sun.xml.writer.global';

    public const M_SXI = 'application/vnd.sun.xml.impress';

    public const M_SXM = 'application/vnd.sun.xml.math';

    public const M_SXW = 'application/vnd.sun.xml.writer';

    public const M_T = 'text/troff';

    public const M_T3 = 'application/x-t3vm-image';

    public const M_TAGLET = 'application/vnd.mynfc';

    public const M_TAO = 'application/vnd.tao.intent-module-archive';

    public const M_TAR = 'application/x-tar';

    public const M_TCAP = 'application/vnd.3gpp2.tcap';

    public const M_TCL = 'application/x-tcl';

    public const M_TEACHER = 'application/vnd.smart.teacher';

    public const M_TEI = 'application/tei+xml';

    public const M_TEICORPUS = 'application/tei+xml';

    public const M_TEX = 'application/x-tex';

    public const M_TEXI = 'application/x-texinfo';

    public const M_TEXINFO = 'application/x-texinfo';

    public const M_TEXT = 'text/plain';

    public const M_TFI = 'application/thraud+xml';

    public const M_TFM = 'application/x-tex-tfm';

    public const M_TGA = 'image/x-tga';

    public const M_THMX = 'application/vnd.ms-officetheme';

    public const M_TIF = 'image/tiff';

    public const M_TIFF = 'image/tiff';

    public const M_TMO = 'application/vnd.tmobile-livetv';

    public const M_TORRENT = 'application/x-bittorrent';

    public const M_TPL = 'application/vnd.groove-tool-template';

    public const M_TPT = 'application/vnd.trid.tpt';

    public const M_TR = 'text/troff';

    public const M_TRA = 'application/vnd.trueapp';

    public const M_TRM = 'application/x-msterminal';

    public const M_TSD = 'application/timestamped-data';

    public const M_TSV = 'text/tab-separated-values';

    public const M_TTC = 'application/x-font-ttf';

    public const M_TTF = 'application/x-font-ttf';

    public const M_TTL = 'text/turtle';

    public const M_TWD = 'application/vnd.simtech-mindmapper';

    public const M_TWDS = 'application/vnd.simtech-mindmapper';

    public const M_TXD = 'application/vnd.genomatix.tuxedo';

    public const M_TXF = 'application/vnd.mobius.txf';

    public const M_TXT = 'text/plain';

    public const M_U32 = 'application/x-authorware-bin';

    public const M_UDEB = 'application/x-debian-package';

    public const M_UFD = 'application/vnd.ufdl';

    public const M_UFDL = 'application/vnd.ufdl';

    public const M_ULX = 'application/x-glulx';

    public const M_UMJ = 'application/vnd.umajin';

    public const M_UNITYWEB = 'application/vnd.unity';

    public const M_UOML = 'application/vnd.uoml+xml';

    public const M_URI = 'text/uri-list';

    public const M_URIS = 'text/uri-list';

    public const M_URLS = 'text/uri-list';

    public const M_USTAR = 'application/x-ustar';

    public const M_UTZ = 'application/vnd.uiq.theme';

    public const M_UU = 'text/x-uuencode';

    public const M_UVA = 'audio/vnd.dece.audio';

    public const M_UVD = 'application/vnd.dece.data';

    public const M_UVF = 'application/vnd.dece.data';

    public const M_UVG = 'image/vnd.dece.graphic';

    public const M_UVH = 'video/vnd.dece.hd';

    public const M_UVI = 'image/vnd.dece.graphic';

    public const M_UVM = 'video/vnd.dece.mobile';

    public const M_UVP = 'video/vnd.dece.pd';

    public const M_UVS = 'video/vnd.dece.sd';

    public const M_UVT = 'application/vnd.dece.ttml+xml';

    public const M_UVU = 'video/vnd.uvvu.mp4';

    public const M_UVV = 'video/vnd.dece.video';

    public const M_UVVA = 'audio/vnd.dece.audio';

    public const M_UVVD = 'application/vnd.dece.data';

    public const M_UVVF = 'application/vnd.dece.data';

    public const M_UVVG = 'image/vnd.dece.graphic';

    public const M_UVVH = 'video/vnd.dece.hd';

    public const M_UVVI = 'image/vnd.dece.graphic';

    public const M_UVVM = 'video/vnd.dece.mobile';

    public const M_UVVP = 'video/vnd.dece.pd';

    public const M_UVVS = 'video/vnd.dece.sd';

    public const M_UVVT = 'application/vnd.dece.ttml+xml';

    public const M_UVVU = 'video/vnd.uvvu.mp4';

    public const M_UVVV = 'video/vnd.dece.video';

    public const M_UVVX = 'application/vnd.dece.unspecified';

    public const M_UVVZ = 'application/vnd.dece.zip';

    public const M_UVX = 'application/vnd.dece.unspecified';

    public const M_UVZ = 'application/vnd.dece.zip';

    public const M_VCARD = 'text/vcard';

    public const M_VCD = 'application/x-cdlink';

    public const M_VCF = 'text/x-vcard';

    public const M_VCG = 'application/vnd.groove-vcard';

    public const M_VCS = 'text/x-vcalendar';

    public const M_VCX = 'application/vnd.vcx';

    public const M_VIS = 'application/vnd.visionary';

    public const M_VIV = 'video/vnd.vivo';

    public const M_VOB = 'video/x-ms-vob';

    public const M_VOR = 'application/vnd.stardivision.writer';

    public const M_VOX = 'application/x-authorware-bin';

    public const M_VRML = 'model/vrml';

    public const M_VSD = 'application/vnd.visio';

    public const M_VSF = 'application/vnd.vsf';

    public const M_VSS = 'application/vnd.visio';

    public const M_VST = 'application/vnd.visio';

    public const M_VSW = 'application/vnd.visio';

    public const M_VTU = 'model/vnd.vtu';

    public const M_VXML = 'application/voicexml+xml';

    public const M_W3D = 'application/x-director';

    public const M_WAD = 'application/x-doom';

    public const M_WAV = 'audio/x-wav';

    public const M_WAX = 'audio/x-ms-wax';

    public const M_WBMP = 'image/vnd.wap.wbmp';

    public const M_WBS = 'application/vnd.criticaltools.wbs+xml';

    public const M_WBXML = 'application/vnd.wap.wbxml';

    public const M_WCM = 'application/vnd.ms-works';

    public const M_WDB = 'application/vnd.ms-works';

    public const M_WDP = 'image/vnd.ms-photo';

    public const M_WEBA = 'audio/webm';

    public const M_WEBM = 'video/webm';

    public const M_WEBP = 'image/webp';

    public const M_WG = 'application/vnd.pmi.widget';

    public const M_WGT = 'application/widget';

    public const M_WKS = 'application/vnd.ms-works';

    public const M_WM = 'video/x-ms-wm';

    public const M_WMA = 'audio/x-ms-wma';

    public const M_WMD = 'application/x-ms-wmd';

    public const M_WMF = 'application/x-msmetafile';

    public const M_WML = 'text/vnd.wap.wml';

    public const M_WMLC = 'application/vnd.wap.wmlc';

    public const M_WMLS = 'text/vnd.wap.wmlscript';

    public const M_WMLSC = 'application/vnd.wap.wmlscriptc';

    public const M_WMV = 'video/x-ms-wmv';

    public const M_WMX = 'video/x-ms-wmx';

    public const M_WMZ = 'application/x-msmetafile';

    public const M_WOFF = 'application/font-woff';

    public const M_WPD = 'application/vnd.wordperfect';

    public const M_WPL = 'application/vnd.ms-wpl';

    public const M_WPS = 'application/vnd.ms-works';

    public const M_WQD = 'application/vnd.wqd';

    public const M_WRI = 'application/x-mswrite';

    public const M_WRL = 'model/vrml';

    public const M_WSDL = 'application/wsdl+xml';

    public const M_WSPOLICY = 'application/wspolicy+xml';

    public const M_WTB = 'application/vnd.webturbo';

    public const M_WVX = 'video/x-ms-wvx';

    public const M_X32 = 'application/x-authorware-bin';

    public const M_X3D = 'model/x3d+xml';

    public const M_X3DB = 'model/x3d+binary';

    public const M_X3DBZ = 'model/x3d+binary';

    public const M_X3DV = 'model/x3d+vrml';

    public const M_X3DVZ = 'model/x3d+vrml';

    public const M_X3DZ = 'model/x3d+xml';

    public const M_XAML = 'application/xaml+xml';

    public const M_XAP = 'application/x-silverlight-app';

    public const M_XAR = 'application/vnd.xara';

    public const M_XBAP = 'application/x-ms-xbap';

    public const M_XBD = 'application/vnd.fujixerox.docuworks.binder';

    public const M_XBM = 'image/x-xbitmap';

    public const M_XDF = 'application/xcap-diff+xml';

    public const M_XDM = 'application/vnd.syncml.dm+xml';

    public const M_XDP = 'application/vnd.adobe.xdp+xml';

    public const M_XDSSC = 'application/dssc+xml';

    public const M_XDW = 'application/vnd.fujixerox.docuworks';

    public const M_XENC = 'application/xenc+xml';

    public const M_XER = 'application/patch-ops-error+xml';

    public const M_XFDF = 'application/vnd.adobe.xfdf';

    public const M_XFDL = 'application/vnd.xfdl';

    public const M_XHT = 'application/xhtml+xml';

    public const M_XHTML = 'application/xhtml+xml';

    public const M_XHVML = 'application/xv+xml';

    public const M_XIF = 'image/vnd.xiff';

    public const M_XLA = 'application/vnd.ms-excel';

    public const M_XLAM = 'application/vnd.ms-excel.addin.macroenabled.12';

    public const M_XLC = 'application/vnd.ms-excel';

    public const M_XLF = 'application/x-xliff+xml';

    public const M_XLM = 'application/vnd.ms-excel';

    public const M_XLS = 'application/vnd.ms-excel';

    public const M_XLSB = 'application/vnd.ms-excel.sheet.binary.macroenabled.12';

    public const M_XLSM = 'application/vnd.ms-excel.sheet.macroenabled.12';

    public const M_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    public const M_XLT = 'application/vnd.ms-excel';

    public const M_XLTM = 'application/vnd.ms-excel.template.macroenabled.12';

    public const M_XLTX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';

    public const M_XLW = 'application/vnd.ms-excel';

    public const M_XM = 'audio/xm';

    public const M_XML = 'application/xml';

    public const M_XO = 'application/vnd.olpc-sugar';

    public const M_XOP = 'application/xop+xml';

    public const M_XPI = 'application/x-xpinstall';

    public const M_XPL = 'application/xproc+xml';

    public const M_XPM = 'image/x-xpixmap';

    public const M_XPR = 'application/vnd.is-xpr';

    public const M_XPS = 'application/vnd.ms-xpsdocument';

    public const M_XPW = 'application/vnd.intercon.formnet';

    public const M_XPX = 'application/vnd.intercon.formnet';

    public const M_XSL = 'application/xml';

    public const M_XSLT = 'application/xslt+xml';

    public const M_XSM = 'application/vnd.syncml+xml';

    public const M_XSPF = 'application/xspf+xml';

    public const M_XUL = 'application/vnd.mozilla.xul+xml';

    public const M_XVM = 'application/xv+xml';

    public const M_XVML = 'application/xv+xml';

    public const M_XWD = 'image/x-xwindowdump';

    public const M_XYZ = 'chemical/x-xyz';

    public const M_XZ = 'application/x-xz';

    public const M_YANG = 'application/yang';

    public const M_YIN = 'application/yin+xml';

    public const M_Z1 = 'application/x-zmachine';

    public const M_Z2 = 'application/x-zmachine';

    public const M_Z3 = 'application/x-zmachine';

    public const M_Z4 = 'application/x-zmachine';

    public const M_Z5 = 'application/x-zmachine';

    public const M_Z6 = 'application/x-zmachine';

    public const M_Z7 = 'application/x-zmachine';

    public const M_Z8 = 'application/x-zmachine';

    public const M_ZAZ = 'application/vnd.zzazz.deck+xml';

    public const M_ZIP = 'application/zip';

    public const M_ZIR = 'application/vnd.zul';

    public const M_ZIRZ = 'application/vnd.zul';

    public const M_ZMM = 'application/vnd.handheld-entertainment+xml';

    public const M_123 = 'application/vnd.lotus-1-2-3';

    public const M_PEXE = 'application/vnd.microsoft.portable-executable';

    public const M_EXE = 'application/exe';

    public const M_DEXE = 'application/dos-exe';

    public const M_XEXE = 'application/x-winexe';

    public const M_MDEXE = 'application/msdos-windows';

    public const M_MSP = 'application/x-msdos-program';

    public const M_XMDEXE = 'application/x-msdownload';

    /**
     * Get mime from file extension
     *
     * @param string $extension Extension
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function extensionToMime(string $extension) : string
    {
        try {
            return (string) (self::getByName('M_' . \strtoupper($extension)) ?? 'application/octet-stream');
        } catch (\Throwable $_) {
            return 'application/octet-stream';
        }
    }

    /**
     * Get the file extension from a mime
     *
     * @param string $mime Mime
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public static function mimeToExtension(string $mime) : ?string
    {
        switch($mime) {
            case self::M_PDF:
                return 'pdf';
            case self::M_JPEG:
            case self::M_JPG:
                return 'jpg';
            case self::M_PNG:
                return 'png';
            case self::M_SVG:
                return 'svg';
            case self::M_BMP:
                return 'bmp';
            case self::M_GIF:
                return 'gif';
            case self::M_HTML:
            case self::M_HTM:
                return 'htm';
            case self::M_DOCX:
                return 'docx';
            case self::M_DOC:
                return 'doc';
            case self::M_ODT:
                return 'odt';
            case self::M_XLSX:
                return 'xlsx';
            case self::M_XLA:
            case self::M_XLS:
                return 'xls';
            case self::M_ODS:
                return 'ods';
            case self::M_PPTX:
                return 'pptx';
            case self::M_PPT:
                return 'ppt';
            case self::M_ODP:
                return 'odp';
            case self::M_CSV:
                return 'csv';
            case self::M_XML:
                return 'xml';
            case self::M_JSON:
                return 'json';
            case self::M_ZIP:
                return 'zip';
            case self::M_7Z:
                return '7z';
            case self::M_RAR:
                return 'rar';
            case self::M_TAR:
                return 'tar';
            case self::M_MP3:
                return 'mp3';
            case self::M_MP4:
                return 'mp4';
            case self::M_PEXE:
            case self::M_EXE:
            case self::M_DEXE:
            case self::M_XEXE:
            case self::M_MDEXE:
            case self::M_MSP:
            case self::M_XMDEXE:
                return 'exe';
            default:
                return null;
        }
    }
}
