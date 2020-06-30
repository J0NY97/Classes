<?php
	class Color
	{
		public $red;
		public $green;
		public $blue;
		static $verbose = False;

		function __construct(array $rgb)
		{
			if (isset($rgb["rgb"]))
			{
				$color_int = intval($rgb["rgb"], 10);
				$this->red = intval($color_int / 65536, 10);
				$this->green = intval($color_int % 65536 / 256, 10);
				$this->blue = intval($color_int % 65536 % 256, 10);
			}
			else if (isset($rgb["red"]) && isset($rgb["green"]) && isset($rgb["blue"]))
			{
				$this->red = intval($rgb["red"], 10);
				$this->green = intval($rgb["green"], 10);
				$this->blue = intval($rgb["blue"], 10);
			}
			if (self::$verbose)
				print ($this." constructed.\n");
		}

		function __destruct()
		{
			if (self::$verbose)
				print ($this." destructed.\n");
		}

		function __toString()
		{
			return (sprintf("Color( red: %3d, green: %3d, blue: %3d )", $this->red, $this->green, $this->blue));
		}

		static function doc()
		{
			$file_name = "Color.doc.txt";
			if (file_exists($file_name))
				return (file_get_contents($file_name));
			else
				return ("Doc file doesn't exist\n");
		}

		function add($colorObject)
		{
			$rgb_arr["red"] = $this->red + $colorObject->red;
			$rgb_arr["green"] = $this->green + $colorObject->green;
			$rgb_arr["blue"] = $this->blue + $colorObject->blue;
			return (new Color($rgb_arr));
		}

		function sub($colorObject)
		{
			$rgb_arr["red"] = $this->red - $colorObject->red;
			$rgb_arr["green"] = $this->green - $colorObject->green;
			$rgb_arr["blue"] = $this->blue - $colorObject->blue;
			return (new Color($rgb_arr));
		}

		function mult($colorObject)
		{
			$rgb_arr["red"] = $this->red * $colorObject->red;
			$rgb_arr["green"] = $this->green * $colorObject->green;
			$rgb_arr["blue"] = $this->blue * $colorObject->blue;
			return (new Color($rgb_arr));
		}
	}
?>
